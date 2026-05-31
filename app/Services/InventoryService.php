<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Collection;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    /**
     * Atomically restore inventory for all products and collections in an order.
     * Uses DB::raw for race-condition-safe updates.
     *
     * @param Order $order Must have 'products' and 'collections' loaded.
     * @return void
     */
    public static function restoreOrderInventory(Order $order): void
    {
        $order->loadMissing(['products', 'collections.products']);

        $productIds = collect();
        $collectionIds = collect();

        // Restore direct products
        $order->products->each(function ($product) use ($productIds) {
            $qty = $product->pivot->quantity;

            if ($qty > 0) {
                Product::where('id', $product->id)
                    ->update(['quantity' => DB::raw("quantity + {$qty}")]);
                $productIds->push($product->id);
            }
        });

        // Restore collection products
        $order->collections->each(function ($collection) use ($productIds, $collectionIds) {
            $orderQty = $collection->pivot->quantity;

            if ($orderQty > 0) {
                $collectionIds->push($collection->id);
                $collection->products->each(function ($product) use ($orderQty, $productIds) {
                    $restoreQty = $orderQty * $product->pivot->quantity;

                    if ($restoreQty > 0) {
                        Product::where('id', $product->id)
                            ->update(['quantity' => DB::raw("quantity + {$restoreQty}")]);
                        $productIds->push($product->id);
                    }
                });
            }
        });

        Log::info("InventoryService: Restored inventory for Order #{$order->id}");

        // Resync updated items with Meta Catalog
        self::syncWithMeta($productIds, $collectionIds);
    }

    /**
     * Atomically deduct inventory for all products and collections in an order.
     * Uses DB::raw for race-condition-safe updates.
     *
     * @param Order $order Must have 'products' and 'collections' loaded.
     * @return void
     */
    public static function deductOrderInventory(Order $order): void
    {
        $order->loadMissing(['products', 'collections.products']);

        $productIds = collect();
        $collectionIds = collect();

        // Deduct direct products
        $order->products->each(function ($product) use ($productIds) {
            $qty = $product->pivot->quantity;

            if ($qty > 0) {
                Product::where('id', $product->id)
                    ->update(['quantity' => DB::raw("GREATEST(quantity - {$qty}, 0)")]);
                $productIds->push($product->id);
            }
        });

        // Deduct collection products
        $order->collections->each(function ($collection) use ($productIds, $collectionIds) {
            $orderQty = $collection->pivot->quantity;

            if ($orderQty > 0) {
                $collectionIds->push($collection->id);
                $collection->products->each(function ($product) use ($orderQty, $productIds) {
                    $deductQty = $orderQty * $product->pivot->quantity;

                    if ($deductQty > 0) {
                        Product::where('id', $product->id)
                            ->update(['quantity' => DB::raw("GREATEST(quantity - {$deductQty}, 0)")]);
                        $productIds->push($product->id);
                    }
                });
            }
        });

        Log::info("InventoryService: Deducted inventory for Order #{$order->id}");

        // Resync updated items with Meta Catalog
        self::syncWithMeta($productIds, $collectionIds);
    }

    /**
     * Resync products and collections with the Meta Catalog.
     *
     * @param \Illuminate\Support\Collection $productIds
     * @param \Illuminate\Support\Collection $collectionIds
     * @return void
     */
    protected static function syncWithMeta($productIds, $collectionIds): void
    {
        $uniqueProductIds = $productIds->unique()->filter();
        $uniqueCollectionIds = $collectionIds->unique()->filter();

        if ($uniqueProductIds->isEmpty() && $uniqueCollectionIds->isEmpty()) {
            return;
        }

        try {
            $itemsToSync = collect();

            // 1. Load products
            if ($uniqueProductIds->isNotEmpty()) {
                $products = Product::with([
                    'images',
                    'thumbnail',
                    'brand',
                    'subcategories.category.supercategory'
                ])->whereIn('id', $uniqueProductIds)->get();

                foreach ($products as $product) {
                    $itemsToSync->push($product);
                }
            }

            // 2. Load collections
            if ($uniqueCollectionIds->isNotEmpty()) {
                $collections = Collection::with([
                    'images',
                    'thumbnail',
                    'products'
                ])->whereIn('id', $uniqueCollectionIds)->get();

                foreach ($collections as $collection) {
                    $itemsToSync->push($collection);
                }
            }

            $catalogService = app(\App\Services\Front\meta\MetaCatalogService::class);

            // Batch sync all updated items (active will be published, disabled will be staged)
            if ($itemsToSync->isNotEmpty()) {
                $catalogService->syncItems($itemsToSync);
            }
        } catch (\Throwable $e) {
            Log::error("InventoryService: Meta Catalog sync failed: " . $e->getMessage());
        }
    }

    /**
     * Restore coupon usage count for the order's coupon.
     *
     * @param Order $order Must have 'coupon' loaded.
     * @return void
     */
    public static function restoreCoupon(Order $order): void
    {
        $order->loadMissing('coupon');

        if ($order->coupon && !is_null($order->coupon->number)) {
            $order->coupon->increment('number');
            Log::info("InventoryService: Restored coupon #{$order->coupon_id} for Order #{$order->id}");
        }
    }

    /**
     * Delete gift points awarded for this order.
     *
     * @param Order $order
     * @return void
     */
    public static function deleteGiftPoints(Order $order): void
    {
        $deleted = $order->points()->delete();
        Log::info("InventoryService: Deleted {$deleted} gift point records for Order #{$order->id}");
    }

    /**
     * Restore wallet balance that was used for this order.
     *
     * @param Order $order Must have 'transactions' and 'user' loaded.
     * @return void
     */
    public static function restoreWallet(Order $order): void
    {
        $order->loadMissing(['transactions', 'user']);

        $walletTx = $order->transactions
            ->where('payment_method_id', PaymentMethod::Wallet->value)
            ->where('payment_status_id', PaymentStatus::Paid->value)
            ->first();

        if ($walletTx && $walletTx->payment_amount > 0) {
            $order->user->update([
                'balance' => DB::raw("balance + {$walletTx->payment_amount}"),
            ]);

            $walletTx->update([
                'payment_status_id' => PaymentStatus::Refunded->value,
            ]);

            Log::info("InventoryService: Restored wallet balance {$walletTx->payment_amount} for Order #{$order->id}");
        }
    }

    /**
     * Restore points that were used as payment for this order.
     *
     * @param Order $order Must have 'transactions' and 'user' loaded.
     * @return void
     */
    public static function restoreUsedPoints(Order $order): void
    {
        $order->loadMissing(['transactions', 'user']);

        $pointsTx = $order->transactions
            ->where('payment_method_id', PaymentMethod::Points->value)
            ->where('payment_status_id', PaymentStatus::Paid->value)
            ->first();

        if ($pointsTx) {
            $details = json_decode($pointsTx->payment_details, true);
            $usedPoints = $details['points'] ?? 0;

            if ($usedPoints > 0) {
                $order->user->points()->create([
                    'order_id' => $order->id,
                    'value' => $usedPoints,
                    'status' => 1, // Active
                ]);

                $pointsTx->update([
                    'payment_status_id' => PaymentStatus::Refunded->value,
                ]);

                Log::info("InventoryService: Restored {$usedPoints} points for Order #{$order->id}");
            }
        }
    }

    /**
     * Full cancellation: restore inventory, coupon, wallet, points, gift points.
     * Wraps everything in a transaction for atomicity.
     *
     * @param Order $order
     * @param bool $softDelete Whether to soft-delete the order after cancellation.
     * @return bool
     */
    public static function fullCancellation(Order $order, bool $softDelete = true): bool
    {
        $order->loadMissing([
            'products', 'collections.products',
            'coupon', 'transactions', 'user', 'points',
        ]);

        // Guard: don't restore if already cancelled/rejected
        $alreadyCancelledStatuses = [
            OrderStatus::CancellationApproved->value,
            OrderStatus::Rejected->value,
        ];

        if (in_array($order->status_id, $alreadyCancelledStatuses) && $order->trashed()) {
            Log::warning("InventoryService: Order #{$order->id} already cancelled, skipping restoration.");
            return false;
        }

        DB::beginTransaction();

        try {
            // 1. Restore inventory
            self::restoreOrderInventory($order);

            // 2. Restore coupon
            self::restoreCoupon($order);

            // 3. Restore wallet
            self::restoreWallet($order);

            // 4. Restore used points
            self::restoreUsedPoints($order);

            // 5. Delete gift points
            self::deleteGiftPoints($order);

            // 6. Soft delete if requested
            if ($softDelete) {
                $order->delete();
            }

            DB::commit();

            Log::info("InventoryService: Full cancellation completed for Order #{$order->id}");
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("InventoryService: Full cancellation failed for Order #{$order->id}: {$th->getMessage()}");
            throw $th;
        }
    }
}
