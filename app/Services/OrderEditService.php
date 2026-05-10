<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Zone;
use App\Models\Product;
use App\Models\Collection;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\OrderEditLog;
use App\Services\CouponService;
use App\Exceptions\InsufficientStockException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection as SupportCollection;
use App\Services\InventoryService;

class OrderEditService
{
    private Order $order;
    private SupportCollection $products;
    private SupportCollection $collections;

    private array $couponData = [
        'coupon_items_points' => 0,
        'coupon_order_points' => 0,
        'coupon_items_discount' => 0,
        'coupon_order_discount' => 0,
        'free_shipping' => false,
    ];

    private float $items_base_prices = 0;
    private float $items_final_prices = 0;
    private float $items_best_prices = 0;
    private float $items_discounts = 0;
    private float $offers_discounts = 0;
    private float $order_discount = 0;
    private float $delivery_fees = 0;
    private int $items_total_points = 0;
    private int $offers_total_points = 0;
    private int $order_points = 0;
    private int $total_points = 0;
    private float $total = 0;

    public function __construct(Order $order)
    {
        $this->order = $order->load([
            'products', 'collections', 'invoice',
            'transactions', 'user', 'address', 'coupon', 'points',
        ]);
    }

    /**
     * Take a full snapshot of the current order state.
     */
    public function takeSnapshot(): array
    {
        return [
            'order' => $this->order->toArray(),
            'products' => $this->order->products->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'quantity' => $p->pivot->quantity,
                'original_price' => $p->pivot->original_price,
                'price' => $p->pivot->price,
                'coupon_discount' => $p->pivot->coupon_discount,
                'points' => $p->pivot->points,
                'coupon_points' => $p->pivot->coupon_points,
            ])->toArray(),
            'collections' => $this->order->collections->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'quantity' => $c->pivot->quantity,
                'original_price' => $c->pivot->original_price,
                'price' => $c->pivot->price,
                'coupon_discount' => $c->pivot->coupon_discount,
                'points' => $c->pivot->points,
                'coupon_points' => $c->pivot->coupon_points,
            ])->toArray(),
            'invoice' => $this->order->invoice?->toArray(),
            'transactions' => $this->order->transactions->toArray(),
        ];
    }

    /**
     * Recalculate the order with new items, coupon, address, and payment data.
     * Returns a preview array without persisting changes.
     */
    public function recalculate(
        array $items,
        ?int $couponId,
        int $addressId,
        bool $allowOpening,
        float $walletAmount,
        int $pointsToUse,
        int $paymentMethodId
    ): array {
        $this->processItems($items);
        $this->calculateDeliveryFees($addressId, $allowOpening);
        $this->calculateSubtotals();

        if ($couponId) {
            $this->applyCoupon($couponId);
        }

        $this->calculateOrderOffer();
        $this->calculateTotal($couponId);

        // Cap wallet and points
        $wallet = min($walletAmount, $this->order->user->balance ?? 0);
        $wallet = min($wallet, $this->total);

        $conversionRate = config('settings.points_conversion_rate', 0);
        $maxPoints = $this->order->user->valid_points ?? 0;
        $cappedPoints = min($pointsToUse, $maxPoints);
        $pointsEgp = $conversionRate > 0 ? min($cappedPoints * $conversionRate, $this->total - $wallet) : 0;
        $finalPoints = $conversionRate > 0 ? $pointsEgp / $conversionRate : 0;

        $shouldPay = max(0, $this->total - $wallet - $pointsEgp);

        return [
            'products' => $this->products,
            'collections' => $this->collections,
            'items_base_prices' => $this->items_base_prices,
            'items_final_prices' => $this->items_final_prices,
            'items_best_prices' => $this->items_best_prices,
            'items_discounts' => $this->items_discounts,
            'offers_discounts' => $this->offers_discounts,
            'order_discount' => $this->order_discount,
            'delivery_fees' => $this->delivery_fees,
            'coupon_data' => $this->couponData,
            'total_points' => $this->total_points,
            'total' => $this->total,
            'wallet' => $wallet,
            'points' => $finalPoints,
            'points_egp' => $pointsEgp,
            'should_pay' => $shouldPay,
        ];
    }

    /**
     * Apply the edit inside a DB transaction.
     */
    public function applyEdit(
        array $items,
        ?int $couponId,
        int $addressId,
        bool $allowOpening,
        float $walletAmount,
        int $pointsToUse,
        int $paymentMethodId,
        ?string $notes,
        string $reason = ''
    ): Order {
        DB::beginTransaction();

        try {
            $snapshotBefore = $this->takeSnapshot();

            // 1. Recalculate everything
            $calc = $this->recalculate(
                $items, $couponId, $addressId,
                $allowOpening, $walletAmount, $pointsToUse, $paymentMethodId
            );

            // 2. Validate stock
            $this->validateStock();

            // 3. Reverse old state
            $this->reverseOldInventory();
            $this->reverseOldCoupon();
            $this->reverseOldWallet();
            $this->reverseOldPoints();
            $this->reverseOldGiftPoints();

            // 4. Sync new items
            $this->syncNewItems();

            // 5. Deduct new inventory
            $this->deductNewInventory();

            // 6. Handle new coupon
            if ($couponId) {
                $this->decrementCouponUsage($couponId);
            }

            // 7. Update order record
            $this->updateOrderRecord($addressId, $couponId, $allowOpening, $notes, $calc);

            // 8. Update invoice
            $this->updateInvoice($calc);

            // 9. Handle transactions
            $this->handleTransactions($calc, $paymentMethodId);

            // 10. Add new gift points
            if ($this->total_points > 0) {
                $this->order->user->points()->create([
                    'order_id' => $this->order->id,
                    'value' => $this->total_points,
                    'status' => 0,
                ]);
            }

            // 11. Update status
            $previousStatus = $this->order->status_id;
            if ($previousStatus !== OrderStatus::UnderEditing->value) {
                $this->order->update(['status_id' => OrderStatus::EditApproved->value]);
                $this->order->statuses()->attach(OrderStatus::EditApproved->value);
            } else {
                $this->order->update(['status_id' => OrderStatus::WaitingForApproval->value]);
                $this->order->statuses()->attach(OrderStatus::EditApproved->value);
                $this->order->statuses()->attach(OrderStatus::WaitingForApproval->value);
            }

            // 12. Audit log
            $this->order->refresh();
            $snapshotAfter = $this->takeSnapshot();

            OrderEditLog::create([
                'order_id' => $this->order->id,
                'edited_by' => Auth::id(),
                'snapshot_before' => $snapshotBefore,
                'snapshot_after' => $snapshotAfter,
                'changes_summary' => $this->buildChangesSummary($snapshotBefore, $snapshotAfter),
                'reason' => $reason,
            ]);

            DB::commit();
            return $this->order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // ─── Private Methods ───

    private function processItems(array $items): void
    {
        $productItems = collect($items)->where('type', 'Product');
        $collectionItems = collect($items)->where('type', 'Collection');

        $productIds = $productItems->pluck('id')->toArray();
        $collectionIds = $collectionItems->pluck('id')->toArray();

        $productQuantities = $productItems->pluck('amount', 'id')->toArray();
        $collectionQuantities = $collectionItems->pluck('amount', 'id')->toArray();

        $this->products = !empty($productIds) ? getBestOfferForProducts($productIds) : collect();
        $this->collections = !empty($collectionIds) ? getBestOfferForCollections($collectionIds) : collect();

        $this->products->each(fn($p) => $p->qty = $productQuantities[$p->id] ?? 0);
        $this->collections->each(fn($c) => $c->qty = $collectionQuantities[$c->id] ?? 0);
    }

    private function calculateDeliveryFees(int $addressId, bool $allowOpening): void
    {
        // Check free shipping from offers
        if ($this->products->contains('free_shipping', 1) || $this->collections->contains('free_shipping', 1)) {
            $this->delivery_fees = 0;
            return;
        }
        if ($this->products->contains('offer_free_shipping', true) || $this->collections->contains('offer_free_shipping', true)) {
            $this->delivery_fees = 0;
            return;
        }

        $address = Address::with('city')->find($addressId);
        if (!$address) {
            $this->delivery_fees = 0;
            return;
        }

        $zones = Zone::with(['destinations'])
            ->where('is_active', 1)
            ->whereHas('destinations', fn($q) => $q->where('city_id', $address->city_id))
            ->whereHas('delivery', fn($q) => $q->where('is_active', 1))
            ->get();

        if ($zones->isEmpty()) {
            $this->delivery_fees = 0;
            return;
        }

        $weight = $this->products->sum(fn($p) => ($p->weight ?? 0) * $p->qty)
            + $this->collections->sum(fn($c) => ($c->weight ?? 0) * $c->qty);

        $prices = $zones->map(function ($zone) use ($weight) {
            if ($weight < $zone->min_weight) {
                return ['zone_id' => $zone->id, 'charge' => $zone->min_charge];
            }
            $excess = ceil($weight) - $zone->min_weight;
            return ['zone_id' => $zone->id, 'charge' => $zone->min_charge + ($excess * $zone->kg_charge)];
        });

        $this->delivery_fees = $prices->min('charge')
            + ($allowOpening ? config('settings.allow_to_open_package_price', 0) : 0);
    }

    private function calculateSubtotals(): void
    {
        $allItems = $this->products->merge($this->collections);

        $this->items_base_prices = $allItems->sum(fn($i) => $i->base_price * $i->qty);
        $this->items_final_prices = $allItems->sum(fn($i) => $i->final_price * $i->qty);
        $this->items_best_prices = $allItems->sum(fn($i) => ($i->best_price ?? $i->final_price) * $i->qty);
        $this->items_discounts = $this->items_base_prices - $this->items_final_prices;
        $this->offers_discounts = $this->items_final_prices - $this->items_best_prices;
        $this->items_total_points = (int) $allItems->sum(fn($i) => ($i->points ?? 0) * $i->qty);
        $this->offers_total_points = (int) $allItems->sum(fn($i) => ($i->best_points ?? 0) * $i->qty);
    }

    private function applyCoupon(int $couponId): void
    {
        try {
            $couponService = new CouponService($this->products, $this->collections);
            $result = $couponService->calculateDiscount($couponId, $this->items_best_prices);

            $this->couponData = [
                'coupon_items_points' => $result['coupon_items_points'],
                'coupon_order_points' => $result['coupon_order_points'],
                'coupon_items_discount' => $result['coupon_items_discount'],
                'coupon_order_discount' => $result['coupon_order_discount'],
                'free_shipping' => $result['coupon_free_shipping'] ?? false,
            ];

            $this->products = $result['products_best_coupon'];
            $this->collections = $result['collections_best_coupon'];

            if ($this->couponData['free_shipping']) {
                $this->delivery_fees = 0;
            }
        } catch (\Exception $e) {
            // Coupon invalid — proceed without
        }
    }

    private function calculateOrderOffer(): void
    {
        $orderOffer = Offer::orderOffers()->first();
        $this->order_discount = 0;
        $this->order_points = 0;

        if ($orderOffer) {
            if ($orderOffer->type == 0 && $orderOffer->value <= 100) {
                $this->order_discount = $this->items_best_prices * ($orderOffer->value / 100);
            } elseif ($orderOffer->type == 1) {
                $this->order_discount = min($orderOffer->value, $this->items_best_prices);
            } elseif ($orderOffer->type == 2) {
                $this->order_points = $orderOffer->value;
            }
        }
    }

    private function calculateTotal(?int $couponId): void
    {
        $couponDiscount = $this->couponData['coupon_items_discount'] + $this->couponData['coupon_order_discount'];
        $couponPoints = $this->couponData['coupon_items_points'] + $this->couponData['coupon_order_points'];

        $this->total = max(0, $this->items_best_prices - $this->order_discount - $couponDiscount + $this->delivery_fees);
        $this->total_points = max(0, $this->offers_total_points + $this->order_points - $couponPoints);
    }

    private function validateStock(): void
    {
        foreach ($this->products as $product) {
            $oldQty = $this->order->products->where('id', $product->id)->first()?->pivot->quantity ?? 0;
            $available = $product->quantity + $oldQty;

            if ($product->qty > $available) {
                $name = $product->getTranslation('name', 'ar');
                throw new InsufficientStockException(
                    "المنتج {$name} متوفر منه {$available} فقط"
                );
            }
        }

        foreach ($this->collections as $collection) {
            $oldCollQty = $this->order->collections->where('id', $collection->id)->first()?->pivot->quantity ?? 0;
            foreach ($collection->products as $product) {
                $oldProductReserved = $oldCollQty * $product->pivot->quantity;
                $available = $product->quantity + $oldProductReserved;
                $needed = $collection->qty * $product->pivot->quantity;

                if ($needed > $available) {
                    $name = $product->getTranslation('name', 'ar');
                    throw new InsufficientStockException(
                        "المنتج {$name} في المجموعة متوفر منه {$available} فقط"
                    );
                }
            }
        }
    }

    private function reverseOldInventory(): void
    {
        InventoryService::restoreOrderInventory($this->order);
    }

    private function reverseOldCoupon(): void
    {
        if ($this->order->coupon_id && $this->order->coupon) {
            $coupon = $this->order->coupon;
            if (!is_null($coupon->number)) {
                $coupon->increment('number');
            }
        }
    }

    private function reverseOldWallet(): void
    {
        $walletTx = $this->order->transactions
            ->where('payment_method_id', PaymentMethod::Wallet->value)
            ->where('payment_status_id', PaymentStatus::Paid->value)
            ->first();

        if ($walletTx) {
            $this->order->user->update([
                'balance' => $this->order->user->balance + $walletTx->payment_amount,
            ]);
        }
    }

    private function reverseOldPoints(): void
    {
        $pointsTx = $this->order->transactions
            ->where('payment_method_id', PaymentMethod::Points->value)
            ->where('payment_status_id', PaymentStatus::Paid->value)
            ->first();

        if ($pointsTx) {
            $details = json_decode($pointsTx->payment_details, true);
            $usedPoints = $details['points'] ?? 0;

            if ($usedPoints > 0) {
                $this->order->user->points()->create([
                    'order_id' => $this->order->id,
                    'value' => $usedPoints,
                    'status' => 1,
                ]);
            }
        }
    }

    private function reverseOldGiftPoints(): void
    {
        $this->order->points()->delete();
    }

    private function syncNewItems(): void
    {
        $finalProducts = [];
        foreach ($this->products as $product) {
            $finalProducts[$product->id] = [
                'quantity' => $product->qty,
                'original_price' => $product->original_price ?? 0,
                'price' => ($product->best_price ?? $product->final_price) - ($product->coupon_discount ?? 0),
                'coupon_discount' => $product->coupon_discount ?? 0,
                'points' => $product->best_points ?? 0,
                'coupon_points' => $product->coupon_points ?? 0,
            ];
        }

        $finalCollections = [];
        foreach ($this->collections as $collection) {
            $finalCollections[$collection->id] = [
                'quantity' => $collection->qty,
                'original_price' => $collection->original_price ?? 0,
                'price' => ($collection->best_price ?? $collection->final_price) - ($collection->coupon_discount ?? 0),
                'coupon_discount' => $collection->coupon_discount ?? 0,
                'points' => $collection->best_points ?? 0,
                'coupon_points' => $collection->coupon_points ?? 0,
            ];
        }

        if (!empty($finalProducts)) {
            $this->order->products()->sync($finalProducts);
        } else {
            $this->order->products()->detach();
        }

        if (!empty($finalCollections)) {
            $this->order->collections()->sync($finalCollections);
        } else {
            $this->order->collections()->detach();
        }
    }

    private function deductNewInventory(): void
    {
        $this->order->load(['products', 'collections']);
        InventoryService::deductOrderInventory($this->order);
    }

    private function decrementCouponUsage(int $couponId): void
    {
        $coupon = Coupon::find($couponId);
        if ($coupon && $coupon->number !== null && $coupon->number > 0) {
            $coupon->decrement('number');
        }
    }

    private function updateOrderRecord(int $addressId, ?int $couponId, bool $allowOpening, ?string $notes, array $calc): void
    {
        $totalCount = $this->products->sum('qty') + $this->collections->sum('qty');
        $weight = $this->products->sum(fn($p) => ($p->weight ?? 0) * $p->qty)
            + $this->collections->sum(fn($c) => ($c->weight ?? 0) * $c->qty);

        $this->order->update([
            'address_id' => $addressId,
            'coupon_id' => $couponId,
            'num_of_items' => $totalCount,
            'allow_opening' => $allowOpening,
            'total_weight' => $weight,
            'items_points' => $this->items_total_points,
            'offers_items_points' => $this->offers_total_points,
            'offers_order_points' => $this->order_points,
            'coupon_items_points' => $this->couponData['coupon_items_points'],
            'coupon_order_points' => $this->couponData['coupon_order_points'],
            'gift_points' => $this->total_points,
            'notes' => $notes,
        ]);
    }

    private function updateInvoice(array $calc): void
    {
        $this->order->invoice()->updateOrCreate(
            ['order_id' => $this->order->id],
            [
                'subtotal_base' => $calc['items_base_prices'],
                'items_discount' => $calc['items_discounts'],
                'offers_items_discount' => $calc['offers_discounts'],
                'offers_order_discount' => $calc['order_discount'],
                'coupon_items_discount' => $calc['coupon_data']['coupon_items_discount'],
                'coupon_order_discount' => $calc['coupon_data']['coupon_order_discount'],
                'delivery_fees' => $calc['delivery_fees'],
                'total' => $calc['total'],
            ]
        );
    }

    private function handleTransactions(array $calc, int $paymentMethodId): void
    {
        $invoice = $this->order->invoice;

        // Delete old transactions
        $invoice->transactions()->delete();

        // Wallet transaction
        if ($calc['wallet'] > 0) {
            $invoice->transactions()->create([
                'order_id' => $this->order->id,
                'user_id' => $this->order->user_id,
                'payment_method_id' => PaymentMethod::Wallet->value,
                'payment_status_id' => PaymentStatus::Paid->value,
                'payment_amount' => $calc['wallet'],
                'payment_details' => json_encode([
                    'amount_cents' => number_format($calc['wallet'] * 100, 0, '', ''),
                    'points' => 0,
                    'transaction_id' => null,
                    'source_data_sub_type' => Auth::user()->f_name . ' ' . Auth::user()->l_name,
                ]),
            ]);

            $this->order->user->update([
                'balance' => max(0, $this->order->user->balance - $calc['wallet']),
            ]);
        }

        // Points transaction
        if ($calc['points_egp'] > 0) {
            $invoice->transactions()->create([
                'order_id' => $this->order->id,
                'user_id' => $this->order->user_id,
                'payment_method_id' => PaymentMethod::Points->value,
                'payment_status_id' => PaymentStatus::Paid->value,
                'payment_amount' => $calc['points_egp'],
                'payment_details' => json_encode([
                    'amount_cents' => number_format($calc['points_egp'] * 100, 0, '', ''),
                    'points' => $calc['points'],
                    'transaction_id' => null,
                    'source_data_sub_type' => Auth::user()->f_name . ' ' . Auth::user()->l_name,
                ]),
            ]);

            // Deduct points (oldest first)
            $usedPoints = $calc['points'];
            while ($usedPoints > 0) {
                $oldest = $this->order->user->points()
                    ->where('status', 1)
                    ->where('created_at', '>=', Carbon::now()->subDays(90))
                    ->orderBy('created_at')
                    ->first();

                if (!$oldest) break;

                if ($oldest->value <= $usedPoints) {
                    $usedPoints -= $oldest->value;
                    $oldest->delete();
                } else {
                    $oldest->update(['value' => $oldest->value - $usedPoints]);
                    $usedPoints = 0;
                }
            }
        }

        // Main payment transaction
        if ($calc['should_pay'] > 0) {
            $invoice->transactions()->create([
                'order_id' => $this->order->id,
                'user_id' => $this->order->user_id,
                'payment_method_id' => $paymentMethodId,
                'payment_status_id' => PaymentStatus::Pending->value,
                'payment_amount' => $calc['should_pay'],
                'payment_details' => json_encode([
                    'amount_cents' => number_format($calc['should_pay'] * 100, 0, '', ''),
                    'points' => 0,
                    'transaction_id' => null,
                    'source_data_sub_type' => Auth::user()->f_name . ' ' . Auth::user()->l_name,
                ]),
            ]);
        }
    }

    private function buildChangesSummary(array $before, array $after): array
    {
        $changes = [];

        $oldTotal = $before['invoice']['total'] ?? 0;
        $newTotal = $after['invoice']['total'] ?? 0;
        if ($oldTotal != $newTotal) {
            $changes[] = "Total changed from {$oldTotal} to {$newTotal}";
        }

        $oldItemCount = count($before['products']) + count($before['collections']);
        $newItemCount = count($after['products']) + count($after['collections']);
        if ($oldItemCount != $newItemCount) {
            $changes[] = "Item count changed from {$oldItemCount} to {$newItemCount}";
        }

        $oldCoupon = $before['order']['coupon_id'] ?? null;
        $newCoupon = $after['order']['coupon_id'] ?? null;
        if ($oldCoupon != $newCoupon) {
            $changes[] = "Coupon changed from #{$oldCoupon} to #{$newCoupon}";
        }

        $oldAddress = $before['order']['address_id'] ?? null;
        $newAddress = $after['order']['address_id'] ?? null;
        if ($oldAddress != $newAddress) {
            $changes[] = "Address changed from #{$oldAddress} to #{$newAddress}";
        }

        return $changes;
    }
}
