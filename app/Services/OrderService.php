<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Zone;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Facades\MetaPixel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Services\Front\Payments\PaymentService;
use App\Services\Front\Payments\Gateways\CardGateway;
use App\Services\Front\Payments\Gateways\InstallmentGateway;

class OrderService
{
    private Collection $products;
    private Collection $collections;
    private array $couponData = [
        'coupon_items_points' => 0,
        'coupon_order_points' => 0,
        'coupon_items_discount' => 0,
        'coupon_order_discount' => 0,
    ];

    // Computed totals
    private float $items_total_base_prices = 0;
    private float $items_total_discounts = 0;
    private float $offers_total_discounts = 0;
    private float $order_discount = 0;
    private float $shipping_fees = 0;
    private int $items_total_points = 0;
    private int $offers_total_points = 0;
    private int $order_points = 0;
    private int $total_points_after_coupon = 0;
    private float $total_after_coupon_discount = 0;

    /**
     * Create a new order from the checkout form data.
     *
     * @param array $data Order data from the form
     * @return array ['order' => Order, 'redirect' => ?string]
     * @throws \Exception
     */
    public function createOrder(array $data): array
    {
        DB::beginTransaction();

        try {
            // Process cart items and calculate totals
            $this->processCartItems();

            // Apply coupon if provided
            if (!empty($data['coupon_id'])) {
                $this->applyCoupon($data['coupon_id']);
            }

            // Calculate totals
            $this->calculateTotals($data);

            // Calculate best zone for shipping
            $bestZoneId = $this->calculateBestZone($data['address_id']);

            // Calculate shipping fees
            $this->calculateShippingFees($bestZoneId, $data['allow_opening'] ?? false);

            // Validate balance and points
            $balance = $this->validateBalance($data['balance_to_use'] ?? 0);
            $points = $this->validatePoints($data['points_to_use'] ?? 0, $balance);
            $pointsEgp = $points * config('settings.points_conversion_rate', 0);

            // Create the order
            $order = $this->buildOrder($data, $bestZoneId);

            // Sync items to order
            $this->syncOrderItems($order);

            // Attach order status
            $order->statuses()->attach(OrderStatus::Created->value);

            // Create invoice
            $invoice = $this->createInvoice($order, $balance, $pointsEgp);

            // Handle balance transaction
            if ($balance > 0) {
                $this->handleBalanceTransaction($invoice, $order, $balance);
            }

            // Handle points transaction
            if ($points > 0) {
                $this->handlePointsTransaction($invoice, $order, $points, $pointsEgp);
            }

            // Add gift points to user
            if ($this->total_points_after_coupon > 0) {
                $this->addGiftPoints($order, $this->total_points_after_coupon);
            }

            // Calculate amount to pay
            $shouldPay = $this->total_after_coupon_discount + $this->shipping_fees - $balance - $pointsEgp;

            // Create payment transaction
            $transaction = null;
            if ($shouldPay > 0) {
                $transaction = $this->createPaymentTransaction($invoice, $order, $shouldPay, $data['payment_method_id']);
            }

            // Update inventory
            $this->updateInventory($order);

            // Decrement coupon usage
            if (!empty($data['coupon_id'])) {
                $this->decrementCouponUsage($data['coupon_id']);
            }

            // Clear cart
            $this->clearCart();

            // Send Meta Pixel event
            $this->sendMetaPixelEvent($order);

            // Handle payment gateway redirect
            $redirect = $this->handlePaymentGateway($order, $transaction, $data['payment_method_id'], $shouldPay);

            DB::commit();

            return [
                'order' => $order,
                'redirect' => $redirect,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Process cart items and fetch product/collection data.
     */
    private function processCartItems(): void
    {
        $content = Cart::instance('cart')->content();

        $productsQuantities = $content->where('options.type', 'Product')->pluck('qty', 'id');
        $collectionsQuantities = $content->where('options.type', 'Collection')->pluck('qty', 'id');

        $productIds = $productsQuantities->keys()->toArray();
        $collectionIds = $collectionsQuantities->keys()->toArray();

        $this->products = !empty($productIds) ? getBestOfferForProducts($productIds) : collect();
        $this->collections = !empty($collectionIds) ? getBestOfferForCollections($collectionIds) : collect();

        $this->products->each(fn($p) => $p->qty = $productsQuantities[$p->id] ?? 0);
        $this->collections->each(fn($c) => $c->qty = $collectionsQuantities[$c->id] ?? 0);
    }

    /**
     * Apply coupon to products and collections.
     */
    private function applyCoupon(int $couponId): void
    {
        try {
            $couponService = new CouponService($this->products, $this->collections);

            // Calculate subtotal from actual product/collection prices (not cart which may have stale prices)
            $subtotal = $this->products->sum(fn($p) => ($p->best_price ?? $p->final_price) * $p->qty)
                + $this->collections->sum(fn($c) => ($c->best_price ?? $c->final_price) * $c->qty);

            $result = $couponService->calculateDiscount($couponId, $subtotal);

            $this->couponData['coupon_items_points'] = $result['coupon_items_points'];
            $this->couponData['coupon_order_points'] = $result['coupon_order_points'];
            $this->couponData['coupon_items_discount'] = $result['coupon_items_discount'];
            $this->couponData['coupon_order_discount'] = $result['coupon_order_discount'];
            $this->couponData['free_shipping'] = $result['coupon_free_shipping'] ?? false;
            $this->products = $result['products_best_coupon'];
            $this->collections = $result['collections_best_coupon'];
        } catch (\Exception $e) {
            // Log error if needed, but don't fail the order
        }
    }

    /**
     * Calculate all totals from cart and items.
     */
    private function calculateTotals(array $data): void
    {
        $allItems = $this->products->merge($this->collections);

        // Base prices and discounts
        $this->items_total_base_prices = $allItems->sum(fn($item) => $item->base_price * $item->qty);
        $this->items_total_discounts = $allItems->sum(fn($item) => ($item->base_price - $item->final_price) * $item->qty);

        // Offer discounts
        $this->offers_total_discounts = $allItems->sum(fn($item) => ($item->final_price - ($item->best_price ?? $item->final_price)) * $item->qty);

        // Points
        $this->items_total_points = (int) $allItems->sum(fn($item) => ($item->points ?? 0) * $item->qty);
        $this->offers_total_points = (int) $allItems->sum(fn($item) => ($item->best_points ?? 0) * $item->qty);
        $this->order_points = (int) $allItems->sum(fn($item) => ($item->order_points ?? 0) * $item->qty);

        // Order discount from global order offer
        $orderOffer = \App\Models\Offer::orderOffers()->first();
        $totalAfterOffers = $allItems->sum(fn($item) => ($item->best_price ?? $item->final_price) * $item->qty);

        if ($orderOffer) {
            // Percent Discount
            if ($orderOffer->type == 0 && $orderOffer->value <= 100) {
                $this->order_discount = $totalAfterOffers * ($orderOffer->value / 100);
            }
            // Fixed Discount
            elseif ($orderOffer->type == 1) {
                $this->order_discount = $totalAfterOffers - $orderOffer->value > 0 ? $orderOffer->value : $totalAfterOffers;
            }
        } else {
            $this->order_discount = 0;
        }
        $totalAfterOrderDiscount = $totalAfterOffers - $this->order_discount;

        // Apply coupon discount
        $couponTotalDiscount = $this->couponData['coupon_items_discount'] + $this->couponData['coupon_order_discount'];
        $this->total_after_coupon_discount = max(0, $totalAfterOrderDiscount - $couponTotalDiscount);

        // Calculate points after coupon deductions
        $totalPointsBeforeCoupon = $this->offers_total_points + $this->order_points;
        $couponTotalPoints = $this->couponData['coupon_items_points'] + $this->couponData['coupon_order_points'];
        $this->total_points_after_coupon = max(0, $totalPointsBeforeCoupon - $couponTotalPoints);
    }

    /**
     * Calculate the best shipping zone for the given address.
     */
    private function calculateBestZone(int $addressId): ?int
    {
        $address = Address::with(['city'])->find($addressId);

        if (!$address) {
            return null;
        }

        $zones = Zone::with(['destinations'])
            ->where('is_active', 1)
            ->whereHas('destinations', fn($q) => $q->where('city_id', $address->city_id))
            ->whereHas('delivery', fn($q) => $q->where('is_active', 1))
            ->get();

        if ($zones->isEmpty()) {
            return null;
        }

        $weight = $this->products->sum(fn($p) => ($p->shipping_weight ?? $p->weight ?? 0) * $p->qty)
            + $this->collections->sum(fn($c) => ($c->shipping_weight ?? $c->weight ?? 0) * $c->qty);

        $prices = $zones->map(function ($zone) use ($weight) {
            if ($weight < $zone->min_weight) {
                $charge = $zone->min_charge;
            } else {
                $excess_weight = ceil($weight) - $zone->min_weight;
                $charge = $zone->min_charge + ($excess_weight * $zone->kg_charge);
            }
            return ['zone_id' => $zone->id, 'charge' => $charge];
        });

        $minCharge = $prices->min('charge');
        return $prices->where('charge', $minCharge)->first()['zone_id'] ?? null;
    }

    /**
     * Calculate shipping fees based on zone.
     */
    private function calculateShippingFees(?int $zoneId, bool $allowOpening): void
    {
        // Check if coupon provides free shipping
        if (!empty($this->couponData['free_shipping']) && $this->couponData['free_shipping']) {
            $this->shipping_fees = 0;
            return;
        }

        // Check if the at least one item has a free shipping offer
        if ($this->products->contains('free_shipping', 1) || $this->collections->contains('free_shipping', 1)) {
            $this->shipping_fees = 0;
            return;
        }

        if (!$zoneId) {
            $this->shipping_fees = 0;
            return;
        }

        $zone = Zone::find($zoneId);
        if (!$zone) {
            $this->shipping_fees = 0;
            return;
        }

        $weight = $this->products->sum(fn($p) => ($p->shipping_weight ?? $p->weight ?? 0) * $p->qty)
            + $this->collections->sum(fn($c) => ($c->shipping_weight ?? $c->weight ?? 0) * $c->qty);

        $allowOpeningFees = $allowOpening ? config('settings.allow_to_open_package_price', 0) : 0;

        if ($weight < $zone->min_weight) {
            $this->shipping_fees = $zone->min_charge;
        } else {
            $excess_weight = ceil($weight) - $zone->min_weight;
            $this->shipping_fees = $zone->min_charge + ($excess_weight * $zone->kg_charge);
        }

        $this->shipping_fees += $allowOpeningFees;
    }

    /**
     * Validate and cap balance to use.
     */
    private function validateBalance(float $balance): float
    {
        $maxBalance = Auth::user()->balance ?? 0;
        $balance = min($balance, $maxBalance);
        return min($balance, $this->total_after_coupon_discount + $this->shipping_fees);
    }

    /**
     * Validate and cap points to use.
     */
    private function validatePoints(float $pointsToUse, float $balanceUsed): float
    {
        $conversionRate = config('settings.points_conversion_rate', 0);

        // Prevent division by zero
        if ($conversionRate <= 0) {
            return 0;
        }

        // Cap to user's available points
        $maxAvailablePoints = Auth::user()->valid_points ?? 0;
        $cappedPoints = min($pointsToUse, $maxAvailablePoints);

        // Convert to EGP value
        $pointsValueInEgp = $cappedPoints * $conversionRate;

        // Cap to remaining amount to pay (after balance deduction)
        $remainingToPay = $this->total_after_coupon_discount + $this->shipping_fees - $balanceUsed;
        $finalPointsValueInEgp = min($pointsValueInEgp, max(0, $remainingToPay));

        // Convert back to actual points to use
        $finalPointsToUse = $finalPointsValueInEgp / $conversionRate;

        return $finalPointsToUse;
    }

    /**
     * Build and save the order record.
     */
    private function buildOrder(array $data, ?int $zoneId): Order
    {
        $totalCount = $this->products->sum('qty') + $this->collections->sum('qty');
        $weight = $this->products->sum(fn($p) => ($p->shipping_weight ?? $p->weight ?? 0) * $p->qty)
            + $this->collections->sum(fn($c) => ($c->shipping_weight ?? $c->weight ?? 0) * $c->qty);

        return Order::create([
            'user_id' => Auth::id(),
            'address_id' => $data['address_id'],
            'phone1' => $data['phone1'] ?? null,
            'phone2' => $data['phone2'] ?? null,
            'status_id' => OrderStatus::Created->value,
            'num_of_items' => $totalCount,
            'allow_opening' => $data['allow_opening'] ?? false,
            'zone_id' => $zoneId,
            'coupon_id' => $data['coupon_id'] ?? null,
            'items_points' => $this->items_total_points,
            'offers_items_points' => $this->offers_total_points,
            'offers_order_points' => $this->order_points,
            'coupon_items_points' => $this->couponData['coupon_items_points'],
            'coupon_order_points' => $this->couponData['coupon_order_points'],
            'gift_points' => $this->total_points_after_coupon,
            'total_weight' => $weight,
            'package_desc' => "عروض عدد وأدوات قابلة للكسر برجاء المحافظة على مكونات الشحنة لتفادى التلف أو فقدان مكونات الشحنة",
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Create invoice for the order.
     */
    private function createInvoice(Order $order, float $balance, float $pointsEgp)
    {
        return $order->invoice()->create([
            'subtotal_base' => $this->items_total_base_prices,
            'items_discount' => $this->items_total_discounts,
            'offers_items_discount' => $this->offers_total_discounts,
            'offers_order_discount' => $this->order_discount,
            'coupon_items_discount' => $this->couponData['coupon_items_discount'],
            'coupon_order_discount' => $this->couponData['coupon_order_discount'],
            'delivery_fees' => $this->shipping_fees,
            'total' => ceil($this->total_after_coupon_discount + $this->shipping_fees - $balance - $pointsEgp),
        ]);
    }

    /**
     * Handle balance transaction.
     */
    private function handleBalanceTransaction($invoice, Order $order, float $balance): void
    {
        $invoice->transactions()->create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'payment_method_id' => PaymentMethod::Wallet->value,
            'payment_status_id' => PaymentStatus::Paid->value,
            'payment_amount' => $balance,
            'payment_details' => json_encode([
                "amount_cents" => number_format($balance * 100, 0, '', ''),
                "points" => 0,
                "transaction_id" => null,
                "source_data_sub_type" => Auth::user()->f_name . " " . Auth::user()->l_name
            ]),
        ]);

        // Deduct from user balance
        $order->user->update([
            'balance' => max(0, $order->user->balance - $balance)
        ]);
    }

    /**
     * Handle points transaction.
     */
    private function handlePointsTransaction($invoice, Order $order, int $points, float $pointsEgp): void
    {
        $invoice->transactions()->create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'payment_method_id' => PaymentMethod::Points->value,
            'payment_status_id' => PaymentStatus::Paid->value,
            'payment_amount' => $pointsEgp,
            'payment_details' => json_encode([
                "amount_cents" => number_format($pointsEgp * 100, 0, '', ''),
                "points" => $points,
                "transaction_id" => null,
                "source_data_sub_type" => Auth::user()->f_name . " " . Auth::user()->l_name
            ]),
        ]);

        // Deduct points from user (oldest first, within 90 days)
        $usedPoints = $points;

        while ($usedPoints > 0) {
            $oldestPoints = $order->user->points()
                ->where('status', 1)
                ->where('created_at', '>=', Carbon::now()->subDays(90)->toDateTimeString())
                ->orderBy('created_at')
                ->first();

            if (!$oldestPoints) {
                break;
            }

            if ($oldestPoints->value <= $usedPoints) {
                $usedPoints -= $oldestPoints->value;
                $oldestPoints->delete();
            } else {
                $oldestPoints->update([
                    'value' => $oldestPoints->value - $usedPoints
                ]);
                $usedPoints = 0;
            }
        }
    }

    /**
     * Add gift points to user.
     */
    private function addGiftPoints(Order $order, int $points): void
    {
        $order->user->points()->create([
            'order_id' => $order->id,
            'value' => $points,
            'status' => 0 // Pending
        ]);
    }

    /**
     * Create payment transaction.
     */
    private function createPaymentTransaction($invoice, Order $order, float $amount, int $paymentMethodId)
    {
        return $invoice->transactions()->create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'payment_method_id' => $paymentMethodId,
            'payment_status_id' => PaymentStatus::Pending->value,
            'payment_amount' => $amount,
            'payment_details' => json_encode([
                "amount_cents" => number_format($amount * 100, 0, '', ''),
                "points" => 0,
                "transaction_id" => null,
                "source_data_sub_type" => Auth::user()->f_name . " " . Auth::user()->l_name
            ]),
        ]);
    }

    /**
     * Sync products and collections to order with inventory management.
     */
    private function syncOrderItems(Order $order): void
    {
        $items = $this->products->merge($this->collections);

        $finalProducts = [];
        $finalCollections = [];

        foreach ($items as $item) {
            $pivotData = [
                'orderable_id' => $item->id,
                'quantity' => $item->qty,
                'original_price' => $item->original_price ?? 0,
                'price' => $item->best_price ?? 0,
                'coupon_discount' => $item->coupon_discount ?? 0,
                'points' => $item->best_points ?? 0,
                'coupon_points' => $item->coupon_points ?? 0,
            ];

            if ($item->type == 'Product') {
                $finalProducts[$item->id] = $pivotData;
            } elseif ($item->type == 'Collection') {
                $finalCollections[$item->id] = $pivotData;
            }
        }

        // Sync to order
        if (!empty($finalProducts)) {
            $order->products()->sync($finalProducts);
        }

        if (!empty($finalCollections)) {
            $order->collections()->sync($finalCollections);
        }
    }

    /**
     * Update inventory by deducting ordered quantities.
     */
    private function updateInventory(Order $order): void
    {
        // Deduct products
        $order->products()->each(function ($product) {
            $product->quantity = max(0, $product->quantity - $product->pivot->quantity);
            $product->save();
        });

        // Deduct collections (each collection's products)
        $order->collections()->each(function ($collection) {
            $collection->products()->each(function ($product) use ($collection) {
                $product->quantity = max(0, $product->quantity - ($collection->pivot->quantity * $product->pivot->quantity));
                $product->save();
            });
        });
    }

    /**
     * Decrement the coupon usage count.
     */
    private function decrementCouponUsage(int $couponId): void
    {
        $coupon = Coupon::find($couponId);
        if ($coupon && $coupon->number !== null && $coupon->number > 0) {
            $coupon->decrement('number');
        }
    }

    /**
     * Clear the cart after successful order.
     */
    private function clearCart(): void
    {
        Cart::instance('cart')->destroy();

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }
    }

    /**
     * Send Meta Pixel event.
     */
    private function sendMetaPixelEvent(Order $order): void
    {
        try {
            $products = $order->products->pluck('id')->toArray();
            $collections = $order->collections->pluck('id')->toArray();

            $eventId = MetaPixel::generateEventId();

            $customData = [
                'content_type' => 'product_group',
                'content_ids' => array_merge($products, $collections),
                'currency' => 'EGP',
                'value' => ceil($this->total_after_coupon_discount),
            ];

            MetaPixel::sendEvent(
                "Purchase",
                [],
                $customData,
                $eventId
            );
        } catch (\Exception $e) {
            // Don't fail order for Meta Pixel errors
        }
    }

    /**
     * Handle payment gateway and return redirect URL if needed.
     */
    private function handlePaymentGateway(Order $order, $transaction, int $paymentMethodId, float $shouldPay): ?string
    {
        // Order is fully paid or will be paid on delivery
        if ($shouldPay <= 0 || $paymentMethodId == PaymentMethod::Cash->value) {
            $order->update([
                'status_id' => OrderStatus::WaitingForApproval->value
            ]);
            $order->statuses()->attach(OrderStatus::WaitingForApproval->value);
            return null;
        }

        // // Card payment
        // if ($paymentMethodId == PaymentMethod::Card->value) {
        //     $order->update([
        //         'status_id' => OrderStatus::WaitingForPayment->value
        //     ]);
        //     $order->statuses()->attach(OrderStatus::WaitingForPayment->value);

        //     $cardGateway = new CardGateway();
        //     $payment = new PaymentService($cardGateway);
        //     $clientSecret = $payment->getClientSecret($order, $transaction, "New");

        //     if ($clientSecret) {
        //         return "https://accept.paymob.com/unifiedcheckout/?publicKey=" . env("PAYMOB_PUBLIC_KEY") . "&clientSecret={$clientSecret}";
        //     }
        //     return null;
        // }

        // // Installments payment
        // if ($paymentMethodId == PaymentMethod::Installments->value) {
        //     $order->update([
        //         'status_id' => OrderStatus::WaitingForPayment->value
        //     ]);
        //     $order->statuses()->attach(OrderStatus::WaitingForPayment->value);

        //     $installmentGateway = new InstallmentGateway();
        //     $payment = new PaymentService($installmentGateway);
        //     $clientSecret = $payment->getClientSecret($order, $transaction, "New");

        //     if ($clientSecret) {
        //         return "https://accept.paymob.com/unifiedcheckout/?publicKey=" . env("PAYMOB_PUBLIC_KEY") . "&clientSecret={$clientSecret}";
        //     }
        //     return null;
        // }

        // Electronic wallet or Flash
        if (in_array($paymentMethodId, [PaymentMethod::ElectronicWallet->value, PaymentMethod::Flash->value])) {
            $order->update([
                'status_id' => OrderStatus::WaitingForPayment->value
            ]);
            $order->statuses()->attach(OrderStatus::WaitingForPayment->value);
            return null;
        }

        return null;
    }
}
