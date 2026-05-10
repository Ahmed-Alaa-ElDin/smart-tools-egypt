<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Offer;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Zone;
use App\Models\Product;
use App\Models\Collection;
use Livewire\Component;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Services\CouponService;
use App\Services\OrderEditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class EditOrderForm extends Component
{
    public Order $order;
    public $zones;
    public $products_id = [];
    public $collections_id = [];
    public $coupon_discount_percentage;

    public $customerId, $addressId;
    public $customer,
        $default_address,
        $default_phone,
        $items,
        $coupon_id,
        $wallet = 0.00,
        $points = 0,
        $points_egp = 0.00,
        $payment_method = 1,
        $notes = "",
        $allowToOpenPackage = false,
        $edit_reason = "";

    public $delivery_fees,
        $zone_id,
        $subtotal = 0.00,
        $items_base_prices = 0.00,
        $items_final_prices = 0.00,
        $items_best_prices = 0.00,
        $items_discounts = 0.00,
        $items_discounts_percentage = 0,
        $offers_discounts = 0,
        $offers_discounts_percentage = 0,
        $offers_free_shipping = false,
        $items_best_points = 0,
        $order_discount = 0.00,
        $order_discount_percentage = 0,
        $order_points = 0,
        $total_points = 0,
        $total = 0.00,
        $total_after_wallet = 0.00;

    public $products_after_coupon = [],
        $collections_after_coupon = [],
        $coupon_discount = 0.00,
        $coupon_points = 0,
        $coupon_free_shipping = false,
        $coupon_items_discount = 0.00,
        $coupon_order_discount = 0.00,
        $coupon_items_points = 0,
        $coupon_order_points = 0;

    public $best_items,
        $best_products,
        $best_collections,
        $items_total_quantities = 0,
        $items_total_weights = 0;

    public $make_order = false;

    // Track original totals for diff display
    public $original_total = 0.00;

    protected $rules = [
        'customer' => 'required',
        'default_address' => 'required_with:customer',
        'default_phone' => 'required_with:customer',
        'items' => 'array|min:1',
        'payment_method' => 'required|in:1,2,3,4,5',
    ];

    public function messages()
    {
        return [
            'customer.required' => __('admin/ordersPages.Please select a customer first'),
            'default_address.required_with' => __('admin/ordersPages.Please select the default address'),
            'default_phone.required_with' => __('admin/ordersPages.Please select the default phone number'),
            'items.min' => __('admin/ordersPages.Please select the products (at least one product)'),
            'payment_method.required' => __('admin/ordersPages.Please select the payment method'),
            'payment_method.in' => __('admin/ordersPages.Please select the payment method'),
        ];
    }

    protected $listeners = [
        'setUserData',
        'setProductsData',
        'setPaymentData',
    ];

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->customerId = $order->user_id;

        // Pre-populate customer data
        $this->customer = $order->user->toArray();
        $this->default_address = $order->address_id;

        $defaultPhone = $order->user->phones->where('default', 1)->first();
        $this->default_phone = $defaultPhone ? $defaultPhone->id : null;

        // Pre-populate items from existing order
        $items = [];
        foreach ($order->products as $product) {
            $items[] = [
                'id' => $product->id,
                'type' => 'Product',
                'amount' => $product->pivot->quantity,
            ];
        }
        foreach ($order->collections as $collection) {
            $items[] = [
                'id' => $collection->id,
                'type' => 'Collection',
                'amount' => $collection->pivot->quantity,
            ];
        }
        $this->items = $items;

        // Pre-populate payment data
        $this->coupon_id = $order->coupon_id;
        $this->notes = $order->notes;
        $this->allowToOpenPackage = $order->allow_opening;

        // Get main payment method from transactions
        $mainTx = $order->transactions
            ->whereIn('payment_method_id', [
                PaymentMethod::Cash->value,
                // PaymentMethod::Card->value,
                // PaymentMethod::Installments->value,
                PaymentMethod::ElectronicWallet->value,
                PaymentMethod::Flash->value,
            ])
            ->first();
        $this->payment_method = $mainTx ? $mainTx->payment_method_id : PaymentMethod::Cash->value;

        // Wallet
        $walletTx = $order->transactions
            ->where('payment_method_id', PaymentMethod::Wallet->value)
            ->where('payment_status_id', PaymentStatus::Paid->value)
            ->first();
        $this->wallet = $walletTx ? $walletTx->payment_amount : 0;

        // Points
        $pointsTx = $order->transactions
            ->where('payment_method_id', PaymentMethod::Points->value)
            ->where('payment_status_id', PaymentStatus::Paid->value)
            ->first();
        if ($pointsTx) {
            $details = json_decode($pointsTx->payment_details, true);
            $this->points = $details['points'] ?? 0;
        }

        // Store original total for comparison
        $this->original_total = $order->invoice?->total ?? 0;
    }

    public function render()
    {
        return view('livewire.admin.orders.edit-order-form');
    }

    public function setUserData($data)
    {
        $this->customer = $data['customer'];
        $this->default_address = $data['default_address'];
        $this->default_phone = $data['default_phone'];
        $this->validate();
    }

    public function setProductsData($data)
    {
        $this->items = $data['products'];
        $this->validate();
    }

    public function setPaymentData($data)
    {
        $this->coupon_id = $data['coupon_id'];
        $this->wallet = $data['wallet'];
        $this->points = $data['points'];
        $this->payment_method = $data['payment_method'];
        $this->validate();
    }

    public function getOrderData($apply_edit = false)
    {
        $this->make_order = $apply_edit;
        $this->validate();
        $this->calculate();

        if ($this->make_order) {
            if ($this->total_after_wallet < 0 || $this->total < 0) {
                $this->dispatch('displayOrderSummary');
            } else {
                $this->applyEdit();
            }
        } else {
            $this->dispatch('displayOrderSummary');
        }
    }

    // Calculate Order Cost (same logic as OrderForm::calculate)
    public function calculate()
    {
        $this->getProducts();
        $this->getDeliveryFees();
        $this->getSubTotal();

        if ($this->coupon_id) {
            $couponDiscounts = (new CouponService($this->best_products, $this->best_collections))
                ->calculateDiscount($this->coupon_id, $this->items_best_prices);

            $this->best_products = $couponDiscounts['products_best_coupon'];
            $this->best_collections = $couponDiscounts['collections_best_coupon'];
            $this->coupon_items_discount = $couponDiscounts['coupon_items_discount'];
            $this->coupon_order_discount = $couponDiscounts['coupon_order_discount'];
            $this->coupon_discount = $this->coupon_items_discount + $this->coupon_order_discount;
            $this->coupon_discount_percentage = $this->items_best_prices ? round($this->coupon_discount / $this->items_best_prices * 100, 2) : 0;
            $this->coupon_items_points = $couponDiscounts['coupon_items_points'];
            $this->coupon_order_points = $couponDiscounts['coupon_order_points'];
            $this->coupon_points = $this->coupon_items_points + $this->coupon_order_points;
            $this->coupon_free_shipping = $couponDiscounts['coupon_free_shipping'];
        } else {
            $this->coupon_items_discount = 0.00;
            $this->coupon_order_discount = 0.00;
            $this->coupon_discount = 0.00;
            $this->coupon_discount_percentage = 0;
            $this->coupon_items_points = 0;
            $this->coupon_order_points = 0;
            $this->coupon_points = 0;
            $this->coupon_free_shipping = false;
        }

        $this->getTotal();
    }

    public function getProducts()
    {
        $items = $this->items;

        $itemsQuantities = array_reduce($items, function ($carry, $item) {
            $carry[$item['type']][$item['id']] = $item['amount'];
            return $carry;
        });

        $productsQuantities = $itemsQuantities['Product'] ?? [];
        $this->products_id = array_keys($productsQuantities);

        $collectionsQuantities = $itemsQuantities['Collection'] ?? [];
        $this->collections_id = array_keys($collectionsQuantities);

        $this->best_products = getBestOfferForProducts($this->products_id)?->map(function ($product) use ($productsQuantities) {
            $product->qty = $productsQuantities[$product->id] ?? 0;
            $product->free_shipping = $product->free_shipping || $product->offer_free_shipping;
            $product->after_offer_price = $product->final_price - $product->offer_discount;
            $product->total_weight = $product->weight * $product->qty;
            $product->total_shipping_weight = !$product->free_shipping ? $product->weight * $product->qty : 0;
            $product->total_base_price = $product->base_price * $product->qty;
            $product->total_product_discount = ($product->base_price - $product->final_price) * $product->qty;
            $product->total_product_discount_percent = $product->base_price ? round((($product->base_price - $product->final_price) / $product->base_price) * 100, 2) : 0;
            $product->total_final_price = $product->final_price * $product->qty;
            $product->total_offer_discount = $product->offer_discount * $product->qty;
            $product->total_offer_discount_percent = $product->final_price ? round(($product->offer_discount / $product->final_price) * 100, 2) : 0;
            $product->total_after_offer_price = $product->total_final_price - $product->total_offer_discount;
            $product->total_product_points = $product->points * $product->qty;
            $product->total_offer_points = $product->offer_points * $product->qty;
            $product->total_after_offer_points = $product->total_product_points + $product->total_offer_points;
            $product->coupon_discount = 0;
            $product->coupon_points = 0;
            return $product;
        });

        $this->best_collections = getBestOfferForCollections($this->collections_id)?->map(function ($collection) use ($collectionsQuantities) {
            $collection->qty = $collectionsQuantities[$collection->id] ?? 0;
            $collection->free_shipping = $collection->free_shipping || $collection->offer_free_shipping;
            $collection->after_offer_price = $collection->final_price - $collection->offer_discount;
            $collection->total_weight = $collection->weight * $collection->qty;
            $collection->total_shipping_weight = !$collection->free_shipping ? $collection->weight * $collection->qty : 0;
            $collection->total_base_price = $collection->base_price * $collection->qty;
            $collection->total_collection_discount = ($collection->base_price - $collection->final_price) * $collection->qty;
            $collection->total_collection_discount_percent = $collection->base_price ? round((($collection->base_price - $collection->final_price) / $collection->base_price) * 100, 2) : 0;
            $collection->total_final_price = $collection->final_price * $collection->qty;
            $collection->total_offer_discount = $collection->offer_discount * $collection->qty;
            $collection->total_offer_discount_percent = $collection->final_price ? round(($collection->offer_discount / $collection->final_price) * 100, 2) : 0;
            $collection->total_after_offer_price = $collection->total_final_price - $collection->total_offer_discount;
            $collection->total_collection_points = $collection->points * $collection->qty;
            $collection->total_offer_points = $collection->offer_points * $collection->qty;
            $collection->total_after_offer_points = $collection->total_collection_points + $collection->total_offer_points;
            $collection->coupon_discount = 0;
            $collection->coupon_points = 0;
            return $collection;
        });

        $productTotalQuantities = array_sum($productsQuantities) ?? 0;
        $collectionTotalQuantities = array_sum($collectionsQuantities) ?? 0;
        $this->items_total_quantities = $productTotalQuantities + $collectionTotalQuantities;

        $productsWeights = $this->best_products->map(fn($p) => $p->free_shipping ? 0 : $p->weight * $p->qty);
        $collectionWeights = $this->best_collections->map(fn($p) => $p->free_shipping ? 0 : $p->weight * $p->qty);

        $this->items_total_weights = ($productsWeights->sum() ?? 0) + ($collectionWeights->sum() ?? 0);
    }

    public function getDeliveryFees()
    {
        $items_total_weights = $this->items_total_weights ?? 0;
        $address = Address::findOrFail($this->default_address);

        $zones = Zone::with(['destinations'])
            ->where('is_active', 1)
            ->whereHas('destinations', fn($q) => $q->where('city_id', $address->city_id))
            ->whereHas('delivery', fn($q) => $q->where('is_active', 1))
            ->get();

        $this->zones = $zones;

        $this->validate([
            'zones' => "min:1"
        ], [
            'zones.min' => __("admin/ordersPages.There's no delivery service to this area")
        ]);

        $prices = $zones->map(function ($zone) use ($items_total_weights) {
            if ($items_total_weights < $zone->min_weight) {
                return ['zone_id' => $zone->id, 'charge' => $zone->min_charge];
            }
            return ['zone_id' => $zone->id, 'charge' => $zone->min_charge + ceil($items_total_weights - $zone->min_weight) * $zone->kg_charge];
        });

        $deliveryFeesBeforeAllowToOpen = $prices->min('charge');
        $this->delivery_fees = $deliveryFeesBeforeAllowToOpen + ($this->allowToOpenPackage ? config('settings.allow_to_open_package_price') : 0);

        $best_zone = $prices->filter(fn($price) => $price['charge'] == $deliveryFeesBeforeAllowToOpen);
        $this->zone_id = $best_zone->count() ? $best_zone->first()['zone_id'] : null;

        $productsEligibleForFreeShipping = $this->best_products->some(fn($product) => $product->free_shipping != 0);
        $collectionsEligibleForFreeShipping = $this->best_collections->some(fn($collection) => $collection->free_shipping != 0);

        if ($productsEligibleForFreeShipping || $collectionsEligibleForFreeShipping) {
            $this->delivery_fees = 0;
        }
    }

    public function getSubTotal()
    {
        $best_items = $this->best_products->concat($this->best_collections);

        $this->items_base_prices = $best_items->sum(fn($item) => $item->base_price * $item->qty);
        $this->items_final_prices = $best_items->sum(fn($item) => $item->final_price * $item->qty);
        $this->items_best_prices = $best_items->sum(fn($item) => $item->best_price * $item->qty);
        $this->items_discounts = $this->items_base_prices - $this->items_final_prices;
        $this->items_discounts_percentage = $this->items_base_prices > 0 ? round(($this->items_discounts / $this->items_base_prices) * 100, 0) : 0.00;
        $this->offers_discounts = $this->items_final_prices - $this->items_best_prices;
        $this->offers_discounts_percentage = $this->items_final_prices > 0 ? number_format(($this->offers_discounts / $this->items_final_prices) * 100) : 0;
        $this->items_best_points = $best_items->map(fn($item) => $item->best_points * $item->qty)->sum();

        $orderOffer = Offer::orderOffers()->first();
        if ($orderOffer) {
            if ($orderOffer->type == 0 && $orderOffer->value <= 100) {
                $this->order_discount = $this->items_best_prices * ($orderOffer->value / 100);
                $this->order_discount_percentage = round($orderOffer->value);
            } elseif ($orderOffer->type == 1) {
                $this->order_discount = $this->items_best_prices >= $orderOffer->value ? $orderOffer->value : $this->items_best_prices;
                $this->order_discount_percentage = $this->items_best_prices > 0 ? round(($this->order_discount * 100) / $this->items_best_prices) : 0;
            } elseif ($orderOffer->type == 2) {
                $this->order_points = $orderOffer->value;
            }
        }
    }

    public function getTotal()
    {
        $this->total_points = $this->items_best_points + $this->order_points + $this->coupon_points;
        $this->subtotal = $this->items_best_prices - ($this->coupon_discount ?? 0.00);
        $this->total = $this->items_best_prices - $this->order_discount - $this->coupon_discount + ($this->delivery_fees ?? 0) * !$this->coupon_free_shipping;

        $points_conversion_rate = config('settings.points_conversion_rate');
        $this->points_egp = min($this->points * $points_conversion_rate, $this->total);
        $this->points = $this->points_egp / $points_conversion_rate;
        $this->total_after_wallet = $this->total - $this->points_egp;
        $this->wallet = min($this->wallet, $this->total_after_wallet);
        $this->total_after_wallet -= $this->wallet;

        $this->dispatch('setPaymentDataToPaymentPart', [
            "coupon_id" => $this->coupon_id,
            "wallet" => number_format($this->wallet, 2),
            "points" => number_format($this->points, 0),
            "payment_method" => number_format($this->payment_method, 2),
        ]);
    }

    // Apply the edit using OrderEditService
    public function applyEdit()
    {
        DB::beginTransaction();

        try {
            $editService = new OrderEditService($this->order);

            $editService->applyEdit(
                items: $this->items,
                couponId: $this->coupon_id,
                addressId: $this->default_address,
                allowOpening: $this->allowToOpenPackage,
                walletAmount: $this->wallet,
                pointsToUse: $this->points,
                paymentMethodId: $this->payment_method,
                notes: $this->notes,
                reason: $this->edit_reason
            );

            DB::commit();

            Session::flash('success', __('admin/ordersPages.Order Updated Successfully'));
            redirect()->route('admin.orders.new-orders');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
