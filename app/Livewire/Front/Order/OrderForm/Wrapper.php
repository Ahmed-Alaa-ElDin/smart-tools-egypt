<?php

namespace App\Livewire\Front\Order\OrderForm;

use App\Models\Zone;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Address;
use Livewire\Component;
use App\Facades\MetaPixel;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Traits\Front\EnrichesCartItems;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;

class Wrapper extends Component
{
    use EnrichesCartItems;

    public $address_id;
    public $phone1;
    public $phone2;
    public $coupon_id;
    public $payment_method_id;
    public $notes;
    public $allow_opening = false;
    public $use_balance = false;
    public $balance_to_use = 0;
    public $use_points = false;
    public $points_to_use = 0;
    public $points_egp = 0;

    #[Computed]
    public function items()
    {
        return $this->getEnrichedItems('cart');
    }

    #[On('cartUpdated')]
    public function handleCartUpdated()
    {
        // Just re-render - computed properties will refresh automatically
    }

    #[Computed]
    public function processedItems()
    {
        if (empty($this->items)) {
            return collect([]);
        }

        $items = collect($this->items)->map(function ($item) {
            $qty = $item['cartQty'] ?? 0;

            $item['after_offer_price'] = ($item['final_price'] ?? 0) - ($item['offer_discount'] ?? 0);
            $item['total_weight'] = ($item['weight'] ?? 0) * $qty;
            $item['total_base_price'] = ($item['base_price'] ?? 0) * $qty;
            $item['total_item_discount'] = (($item['base_price'] ?? 0) - ($item['final_price'] ?? 0)) * $qty;
            $item['total_item_discount_percent'] = ($item['base_price'] ?? 0) ? round(((($item['base_price'] ?? 0) - ($item['final_price'] ?? 0)) / ($item['base_price'] ?? 1)) * 100, 2) : 0;
            $item['total_final_price'] = ($item['final_price'] ?? 0) * $qty;
            $item['total_offer_discount'] = ($item['offer_discount'] ?? 0) * $qty;
            $item['total_offer_discount_percent'] = ($item['final_price'] ?? 0) ? round((($item['offer_discount'] ?? 0) / ($item['final_price'] ?? 1)) * 100, 2) : 0;
            $item['total_after_offer_price'] = $item['total_final_price'] - $item['total_offer_discount'];
            $item['total_item_points'] = ($item['points'] ?? 0) * $qty;
            $item['total_offer_points'] = ($item['offer_points'] ?? 0) * $qty;
            $item['total_after_offer_points'] = $item['total_item_points'] + $item['total_offer_points'];

            return $item;
        });

        return $items;
    }

    public function mount()
    {
        $this->items = $this->getEnrichedItems('cart');

        if (Auth::check()) {
            $defaultAddress = Auth::user()->addresses()->where('default', true)->first();
            $this->address_id = $defaultAddress ? $defaultAddress->id : null;
            $defaultPhone = Auth::user()->phones()->where('default', true)->first();
            $this->phone1 = $defaultPhone ? $defaultPhone->phone : null;
        }
    }

    #[On('addressSelected')]
    public function setAddress($addressId)
    {
        $this->address_id = $addressId;
    }

    #[On('couponApplied')]
    public function setCoupon($couponId)
    {
        $this->coupon_id = $couponId;
    }

    #[On('phoneSelected')]
    public function setPhone($data)
    {
        $this->phone1 = $data['phone1'] ?? null;
        $this->phone2 = $data['phone2'] ?? null;
    }

    #[On('paymentMethodSelected')]
    public function setPaymentMethod($paymentMethodId)
    {
        $this->payment_method_id = $paymentMethodId;
    }

    #[On('pointsUpdated')]
    public function setPoints($points, $points_egp)
    {
        $this->points_to_use = $points;
        $this->points_egp = $points_egp;
    }

    #[On('balanceUpdated')]
    public function setBalance($balance)
    {
        $this->balance_to_use = $balance;
    }

    #[On('phoneSelected')]
    public function setPhones($data)
    {
        $this->phone1 = $data['phone1'] ?? null;
        $this->phone2 = $data['phone2'] ?? null;
    }

    #[Computed]
    public function orderOffer()
    {
        return Offer::orderOffers()->first();
    }

    #[Computed]
    public function items_total_quantities()
    {
        return Cart::instance('cart')->count();
    }

    #[Computed]
    public function items_total_weights()
    {
        return $this->processedItems->sum('total_weight');
    }

    #[Computed]
    public function items_total_shipping_weights()
    {
        return $this->processedItems->sum(fn($item) => ($item['free_shipping'] ?? 0) ? 0 : $item['total_weight']);
    }

    #[Computed]
    public function items_total_base_prices()
    {
        return $this->processedItems->sum('total_base_price');
    }

    #[Computed]
    public function items_total_final_prices()
    {
        return $this->processedItems->sum('total_final_price');
    }

    #[Computed]
    public function items_total_discounts()
    {
        return $this->processedItems->sum('total_item_discount');
    }

    #[Computed]
    public function items_discounts_percentage()
    {
        return $this->items_total_base_prices ? round(($this->items_total_discounts * 100) / $this->items_total_base_prices, 2) : 0;
    }

    #[Computed]
    public function total_after_offer_prices()
    {
        return $this->processedItems->sum('total_after_offer_price');
    }

    #[Computed]
    public function offers_total_discounts()
    {
        return $this->processedItems->sum('total_offer_discount');
    }

    #[Computed]
    public function offers_discounts_percentage()
    {
        return $this->items_total_final_prices ? round(($this->offers_total_discounts * 100) / $this->items_total_final_prices, 2) : 0;
    }

    #[Computed]
    public function order_discount_details()
    {
        $orderOffer = $this->orderOffer;
        $total = $this->total_after_offer_prices;
        $discount = 0;
        $percent = 0;
        $points = 0;

        if ($orderOffer) {
            if ($orderOffer->type == Offer::TYPE_PERCENTAGE && $orderOffer->value <= 100) {
                $discount = $total * ($orderOffer->value / 100);
                $percent = round($orderOffer->value);
            } elseif ($orderOffer->type == Offer::TYPE_FIXED) {
                $discount = min($orderOffer->value, $total);
                $percent = $total ? round(($discount * 100) / $total) : 0;
            } elseif ($orderOffer->type == Offer::TYPE_POINTS) {
                $points = $orderOffer->value;
            }
        }

        return [
            'discount' => $discount,
            'percent' => $percent,
            'points' => $points,
        ];
    }

    #[Computed]
    public function order_discount()
    {
        return (float) ($this->order_discount_details['discount'] ?? 0);
    }

    #[Computed]
    public function order_discount_percentage()
    {
        return $this->order_discount_details['percent'] ?? 0;
    }

    #[Computed]
    public function order_points()
    {
        return $this->order_discount_details['points'] ?? 0;
    }

    #[Computed]
    public function coupon_data()
    {
        if (!$this->coupon_id) {
            return [
                'coupon_items_discount' => 0,
                'coupon_order_discount' => 0,
                'coupon_items_points' => 0,
                'coupon_order_points' => 0,
                'coupon_free_shipping' => false,
            ];
        }

        try {
            // Get cart content and separate by type
            $cartContent = Cart::instance('cart')->content();

            $productIds = $cartContent->where('options.type', 'Product')->pluck('id')->unique()->toArray();
            $collectionIds = $cartContent->where('options.type', 'Collection')->pluck('id')->unique()->toArray();

            // Fetch actual model data with best prices (these return collections of objects)
            $products = !empty($productIds) ? getBestOfferForProducts($productIds) : collect();
            $collections = !empty($collectionIds) ? getBestOfferForCollections($collectionIds) : collect();

            // Add qty from cart to each item
            $products = $products->map(function ($product) use ($cartContent) {
                $cartItem = $cartContent->where('id', $product->id)->where('options.type', 'Product')->first();
                $product->qty = $cartItem ? $cartItem->qty : 0;
                return $product;
            });

            $collections = $collections->map(function ($collection) use ($cartContent) {
                $cartItem = $cartContent->where('id', $collection->id)->where('options.type', 'Collection')->first();
                $collection->qty = $cartItem ? $cartItem->qty : 0;
                return $collection;
            });

            $couponService = new \App\Services\CouponService($products, $collections);
            $subtotal = (float) str_replace(',', '', Cart::instance('cart')->subtotal());

            return $couponService->calculateDiscount($this->coupon_id, $subtotal);
        } catch (\Exception $e) {
            return [
                'coupon_items_discount' => 0,
                'coupon_order_discount' => 0,
                'coupon_items_points' => 0,
                'coupon_order_points' => 0,
                'coupon_free_shipping' => false,
            ];
        }
    }

    #[Computed]
    public function coupon_discount()
    {
        return ($this->coupon_data['coupon_items_discount'] ?? 0) + ($this->coupon_data['coupon_order_discount'] ?? 0);
    }

    #[Computed]
    public function coupon_discount_percentage()
    {
        $total = $this->total_after_offer_prices;
        return $total > 0 ? round(($this->coupon_discount * 100) / $total, 2) : 0;
    }

    #[Computed]
    public function coupon_items_points()
    {
        return $this->coupon_data['coupon_items_points'] ?? 0;
    }

    #[Computed]
    public function coupon_order_points()
    {
        return $this->coupon_data['coupon_order_points'] ?? 0;
    }

    #[Computed]
    public function coupon_free_shipping()
    {
        return $this->coupon_data['coupon_free_shipping'] ?? false;
    }

    #[Computed]
    public function total_after_order_discount()
    {
        return ceil($this->total_after_offer_prices - $this->order_discount - $this->coupon_discount);
    }

    #[Computed]
    public function items_free_shipping()
    {
        return $this->processedItems->contains('free_shipping', 1);
    }

    #[Computed]
    public function offers_free_shipping()
    {
        return $this->processedItems->contains('offer_free_shipping', 1);
    }

    #[Computed]
    public function order_offer_free_shipping()
    {
        return $this->orderOffer->free_shipping ?? 0;
    }

    #[Computed]
    public function total_order_free_shipping()
    {
        return $this->items_free_shipping || $this->offers_free_shipping || $this->order_offer_free_shipping || $this->coupon_free_shipping;
    }

    #[Computed]
    public function is_eligible_for_shipping()
    {
        if (Cart::instance('cart')->count() == 0 || !Auth::check() || !$this->address_id) {
            return false;
        }

        $address = Address::with(['city'])->find($this->address_id);
        if (!$address) {
            return false;
        }

        $zonesCount = Zone::where('is_active', 1)
            ->whereHas('destinations', fn($q) => $q->where('city_id', $address->city_id))
            ->whereHas('delivery', fn($q) => $q->where('is_active', 1))
            ->count();

        return $zonesCount > 0;
    }

    #[Computed]
    public function shipping_fees()
    {
        if (!$this->is_eligible_for_shipping) {
            return 0;
        }

        if ($this->total_order_free_shipping) {
            return 0;
        }

        $address = Address::find($this->address_id);
        $zones = Zone::with(['destinations'])
            ->where('is_active', 1)
            ->whereHas('destinations', fn($q) => $q->where('city_id', $address->city_id))
            ->whereHas('delivery', fn($q) => $q->where('is_active', 1))
            ->get();

        $weight = $this->items_total_shipping_weights;

        $prices = $zones->map(function ($zone) use ($weight) {
            if ($weight < $zone->min_weight) {
                $charge = $zone->min_charge;
            } else {
                $excess_weight = ceil($weight) - $zone->min_weight;
                $charge = $zone->min_charge + ($excess_weight * $zone->kg_charge);
            }

            return ['charge' => $charge];
        });

        $shippingFeesBeforeAllowToOpenPackage = $prices->min('charge');

        return $shippingFeesBeforeAllowToOpenPackage;
    }

    #[Computed]
    public function allow_opening_fee()
    {
        return $this->allow_opening ? config('settings.allow_to_open_package_price', 0) : 0;
    }

    #[Computed]
    public function subtotal_final()
    {
        return ceil($this->total_after_order_discount + $this->shipping_fees + ($this->shipping_fees > 0 ? $this->allow_opening_fee : 0) - $this->points_egp - $this->balance_to_use);
    }

    #[Computed]
    public function items_total_points()
    {
        return round($this->processedItems->sum('total_item_points'), 0);
    }

    #[Computed]
    public function offers_total_points()
    {
        return round($this->processedItems->sum('total_offer_points'), 0);
    }

    #[Computed]
    public function after_offers_total_points()
    {
        return round($this->processedItems->sum('total_after_offer_points'), 0);
    }

    #[Computed]
    public function total_points_after_order_points()
    {
        return $this->after_offers_total_points + $this->order_points;
    }

    public function submit()
    {
        if (!$this->is_eligible_for_shipping) {
            $this->dispatch('swalDone', text: __('front/homePage.uneligable for shipping'), icon: 'error');
            return;
        }

        $this->validate([
            'address_id' => 'required|exists:addresses,id',
            'phone1' => 'required',
            'payment_method_id' => 'required',
        ]);

        try {
            $orderService = new \App\Services\OrderService();

            $result = $orderService->createOrder([
                'address_id' => $this->address_id,
                'phone1' => $this->phone1,
                'phone2' => $this->phone2,
                'coupon_id' => $this->coupon_id,
                'balance_to_use' => $this->balance_to_use,
                'points_to_use' => $this->points_to_use,
                'payment_method_id' => $this->payment_method_id,
                'notes' => $this->notes,
                'allow_opening' => $this->allow_opening,
            ]);

            // Handle payment gateway redirect
            if (!empty($result['redirect'])) {
                return redirect()->away($result['redirect']);
            }

            // Send Meta Pixel event
            $this->sendMetaPixelEvent($result['order'], 'InitiateCheckout', MetaPixel::generateEventId());
            $this->sendMetaPixelEvent($result['order'], 'Purchase', MetaPixel::generateEventId());

            // Order created successfully
            Session::flash('success', __('front/homePage.Order placed successfully!'));
            return redirect()->route('front.orders.done')->with('order_id', $result['order']->id);
        } catch (\Exception $e) {
            $this->dispatch('swalDone', text: $e->getMessage(), icon: 'error');
        }
    }

    public function render()
    {
        return view('livewire.front.order.order-form.wrapper');
    }

    /**
     * Send Meta Pixel event.
     */
    private function sendMetaPixelEvent(Order $order, string $event = 'Purchase', string $eventId = null): void
    {
        try {
            $products = $order->products->pluck('id')->toArray();
            $collections = $order->collections->pluck('id')->toArray();

            $customData = [
                'content_type' => 'product_group',
                'content_ids' => array_merge($products, $collections),
                'currency' => 'EGP',
                'value' => ceil($this->subtotal_final),
            ];

            MetaPixel::sendEvent(
                $event,
                [],
                $customData,
                $eventId ?? MetaPixel::generateEventId()
            );


            $this->dispatch(
                "metaPixelEvent",
                eventName: $event,
                userData: [],
                customData: $customData,
                eventId: $eventId,
            );
        } catch (\Exception $e) {
            // Don't fail order for Meta Pixel errors
        }
    }

}
