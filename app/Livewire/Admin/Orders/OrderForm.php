<?php

namespace App\Livewire\Admin\Orders;

use Carbon\Carbon;
use App\Models\Zone;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Address;
use Livewire\Component;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Services\CouponService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Services\Front\Deliveries\Bosta;

class OrderForm extends Component
{
    public $make_order = false;
    public $zones;
    public $products_id = [];
    public $collections_id = [];
    public $coupon_discount_percentage;

    public $customer_id, $address_id;

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
        $allowToOpenPackage = false;

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

    protected $rules = [
        'customer'              =>       'required',
        'default_address'       =>       'required_with:customer',
        'default_phone'         =>       'required_with:customer',
        'items'                 =>       'array|min:1',
        'payment_method'        =>       'required|in:1,2,3,4,5',
    ];

    public function messages()
    {
        return [
            'customer.required'                 =>      __('admin/ordersPages.Please select a customer first'),
            'default_address.required_with'     =>      __('admin/ordersPages.Please select the default address'),
            'default_phone.required_with'       =>      __('admin/ordersPages.Please select the default phone number'),
            'items.min'                         =>      __('admin/ordersPages.Please select the products (at least one product)'),
            'payment_method.required'           =>      __('admin/ordersPages.Please select the payment method'),
            'payment_method.in'                 =>      __('admin/ordersPages.Please select the payment method'),
        ];
    }

    protected $listeners = [
        'setUserData',
        'setProductsData',
        'setPaymentData',
    ];

    public function render()
    {
        return view('livewire.admin.orders.order-form');
    }

    public function getOrderData($make_order = false, $new_order = false)
    {
        $this->make_order = $make_order;

        $this->validate();

        $this->calculate();

        if ($this->make_order) {
            if ($this->total_after_wallet < 0 || $this->total < 0) {
                $this->dispatch('displayOrderSummary');
            } else {
                $this->makeOrder($new_order);
            }
        } else {
            $this->dispatch('displayOrderSummary');
        }
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

    // Calculate Order Cost
    public function calculate()
    {
        // Products Data
        $this->getProducts();

        // Delivery Data
        $this->getDeliveryFees();

        // SubTotal
        $this->getSubTotal();

        // Coupon Data
        if ($this->coupon_id) {
            $couponDiscounts = (new CouponService($this->best_products, $this->best_collections))->calculateDiscount($this->coupon_id, $this->items_best_prices);

            // Add Coupon Data to items data
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

        // Total
        $this->getTotal();
    }

    // Get Best Products Data
    public function getProducts()
    {
        $items = $this->items;

        $itemsQuantities = array_reduce($items, function ($carry, $item) {
            $carry[$item['type']][$item['id']] =  $item['amount'];

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
            $collection->total_collection_points =  $collection->points * $collection->qty;
            $collection->total_offer_points =  $collection->offer_points * $collection->qty;
            $collection->total_after_offer_points =  $collection->total_collection_points + $collection->total_offer_points;

            $collection->coupon_discount =  0;
            $collection->coupon_points =  0;

            return $collection;
        });

        $productTotalQuantities = array_sum($productsQuantities) ?? 0;
        $collectionTotalQuantities = array_sum($collectionsQuantities) ?? 0;
        $this->items_total_quantities = $productTotalQuantities + $collectionTotalQuantities;

        $productsWeights = $this->best_products->map(fn($p) => $p->free_shipping ? 0 : $p->weight * $p->qty);
        $collectionWeights = $this->best_collections->map(fn($p) => $p->free_shipping ? 0 : $p->weight * $p->qty);

        $productsTotalWeights = $productsWeights->sum() ?? 0;
        $collectionTotalWeights = $collectionWeights->sum() ?? 0;
        $this->items_total_weights = $productsTotalWeights + $collectionTotalWeights;
    }

    // Get Delivery Fees
    public function getDeliveryFees()
    {
        // Products Total Weight
        $items_total_weights = $this->items_total_weights ?? 0;

        // Zones Data
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

        // Get the best Delivery Cost
        $prices = $zones->map(function ($zone) use ($items_total_weights) {
            $min_charge = $zone->min_charge;
            $min_weight = $zone->min_weight;
            $kg_charge = $zone->kg_charge;

            if ($items_total_weights < $min_weight) {
                return [
                    'zone_id' => $zone->id,
                    'charge' => $min_charge
                ];
            } else {
                return [
                    'zone_id' => $zone->id,
                    'charge' => $min_charge + ($items_total_weights - $min_weight) * $kg_charge
                ];
            }
        });

        // delivery fees
        $deliveryFeesBeforeAllowToOpen = $prices->min('charge');

        $this->delivery_fees = $deliveryFeesBeforeAllowToOpen + ($this->allowToOpenPackage ? config('settings.allow_to_open_package_price') : 0);

        // best zone
        $best_zone = $prices->filter(function ($price) use ($deliveryFeesBeforeAllowToOpen) {
            return $price['charge'] == $deliveryFeesBeforeAllowToOpen;
        });

        $this->zone_id = $best_zone->count() ? $best_zone->first()['zone_id'] : null;

        // Check if the order's products and collections are free shipping
        $productsEligibleForFreeShipping = $this->best_products->every(fn($product) => $product->free_shipping != 0);

        $collectionsEligibleForFreeShipping = $this->best_collections->every(fn($collection) => $collection->free_shipping != 0);

        if ($productsEligibleForFreeShipping && $collectionsEligibleForFreeShipping) {
            $this->delivery_fees = 0;
        }
    }

    // Calculate Subtotal
    public function getSubTotal()
    {
        $best_items = $this->best_products->concat($this->best_collections);

        // get base prices
        $this->items_base_prices = $best_items->sum(fn($item) => $item->base_price * $item->qty);
        $this->items_final_prices = $best_items->sum(fn($item) => $item->final_price * $item->qty);
        $this->items_best_prices = $best_items->sum(fn($item) => $item->best_price * $item->qty);

        // Get products discounts value
        $this->items_discounts = $this->items_base_prices - $this->items_final_prices;

        // Get products discounts percentage
        $this->items_discounts_percentage = $this->items_base_prices > 0 ? round(($this->items_discounts / $this->items_base_prices) * 100, 0) : 0.00;

        // get discount
        $this->offers_discounts = $this->items_final_prices - $this->items_best_prices;

        // get discount percent
        $this->offers_discounts_percentage = $this->items_final_prices > 0 ? number_format(($this->offers_discounts / $this->items_final_prices) * 100) : 0;

        // get products points
        $this->items_best_points = $best_items->map(function ($item) {
            return $item->best_points * $item->qty;
        })->sum();

        // get Extra discount for total order
        $orderOffer = Offer::orderOffers()->first();

        if ($orderOffer) {
            // Percent Discount
            if ($orderOffer->type == 0 && $orderOffer->value <= 100) {
                $this->order_discount = $this->items_best_prices * ($orderOffer->value / 100);
                $this->order_discount_percentage = round($orderOffer->value);
            }
            // Fixed Discount
            elseif ($orderOffer->type == 1) {
                $this->order_discount = $this->items_best_prices >= $orderOffer->value ? $orderOffer->value : $this->items_best_prices;
                $this->order_discount_percentage = $this->items_best_prices > 0 ? round(($this->order_discount * 100) / $this->items_best_prices) : 0;
            }
            // Points
            elseif ($orderOffer->type == 2) {
                $this->order_points = $orderOffer->value;
            }
        }
    }

    // Calculate Total Cost
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

    // Make Order
    public function makeOrder($new_order = false)
    {
        $default_phones = array_column(array_filter($this->customer['phones'], fn($phone) => $phone['default'] == 1), 'phone');
        $default_phone = count($default_phones) > 0 ? $default_phones[0] : null;
        $non_default_phones =  array_map(fn($phone) => $phone['phone'], array_filter($this->customer['phones'], fn($phone) => $phone['default'] == 0));

        $default_addresses = array_filter($this->customer["addresses"], fn($address) => $address['default'] == 1);
        $default_address = count($default_addresses) > 0 ? array_shift($default_addresses) : null;

        DB::beginTransaction();

        try {
            $this->wallet = $this->total > $this->wallet ? $this->wallet : $this->total;

            $this->points_egp = ($this->total - $this->wallet) > $this->points_egp ? $this->points_egp : (($this->total) - $this->wallet);
            $this->points = floor($this->points_egp / config('settings.points_conversion_rate'));

            // 1 - Items Points
            $items_total_points = round(array_sum(array_column($this->best_products->toArray(), 'total_product_points')), 0) + round(array_sum(array_column($this->best_collections->toArray(), 'total_collection_points')), 0);
            // 2 - Offers Points
            $offers_total_points = round(array_sum(array_column($this->best_products->toArray(), 'total_offer_points')), 0) + round(array_sum(array_column($this->best_collections->toArray(), 'total_offer_points')), 0);
            // 3 - Points After Offers (Items Points + Offers Points)
            $after_offers_total_points = round(array_sum(array_column($this->best_products->toArray(), 'total_after_offer_points')), 0) + round(array_sum(array_column($this->best_collections->toArray(), 'total_after_offer_points')), 0);
            // 4 - Points After Order Points
            $total_points_after_order_points = $after_offers_total_points + $this->order_points;
            // 5 - Points After Coupon Points
            $total_points_after_coupon_points = $total_points_after_order_points + $this->coupon_points;

            // Update the Order
            $order = Order::create([
                'user_id'               =>      $this->customer["id"],
                'address_id'            =>      $default_address ? $default_address["id"] : null,
                'phone1'                =>      $default_phone,
                'phone2'                =>      implode(", ", $non_default_phones),
                'package_type'          =>      'parcel',
                'package_desc'          =>      'عروض عدد وأدوات قابلة للكسر برجاء المحافظة على مكونات الشحنة لتفادى التلف أو فقدان مكونات الشحنة',
                'status_id'             =>      OrderStatus::WaitingForApproval->value,
                'num_of_items'          =>      $this->items_total_quantities,
                'allow_opening'         =>      $this->allowToOpenPackage,
                'zone_id'               =>      $this->zone_id,
                'coupon_id'             =>      $this->coupon_id,
                'items_points'          =>      $items_total_points,
                'offers_items_points'   =>      $offers_total_points,
                'offers_order_points'   =>      $this->order_points,
                'coupon_items_points'   =>      $this->coupon_items_points,
                'coupon_order_points'   =>      $this->coupon_order_points,
                'gift_points'           =>      $total_points_after_coupon_points,
                'total_weight'          =>      $this->items_total_weights,
                'notes'                 =>      $this->notes
            ]);

            // Change the state of the order
            if ($order->statuses()->count() == 0) {
                $order->statuses()->attach([OrderStatus::Created->value, OrderStatus::WaitingForApproval->value]);
            }

            // Create an Invoice for the order
            $invoice = $order->invoice()->updateOrCreate([
                'order_id' => $order->id
            ], [
                'subtotal_base' => $this->items_base_prices,
                'items_discount' => $this->items_discounts,
                'offers_items_discount' => $this->offers_discounts,
                'offers_order_discount' => $this->order_discount,
                'coupon_items_discount' => $this->coupon_items_discount,
                'coupon_order_discount' => $this->coupon_order_discount,
                'delivery_fees' => ($this->delivery_fees ?? 0) * !$this->coupon_free_shipping,
                'total' => $this->total,
            ]);

            // Update user wallet if used
            if ($this->wallet > 0) {
                $invoice->transactions()->updateOrCreate([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'payment_method_id' => PaymentMethod::Wallet->value,
                    'payment_status_id' => PaymentStatus::Paid->value,
                ], [
                    'payment_amount' => $this->wallet,
                    'payment_details' => json_encode([
                        "amount_cents" => number_format($this->wallet * 100, 0, '', ''),
                        "points" => 0,
                        "transaction_id" => null,
                        "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                    ]),
                ]);

                $order->user->update([
                    'balance' => $order->user->balance - $this->wallet > 0 ? $order->user->balance - $this->wallet : 0
                ]);
            }

            // Update user points if used
            if ($this->points_egp > 0) {
                $used_points = $this->points;

                $invoice->transactions()->updateOrCreate([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'payment_method_id' => PaymentMethod::Points->value,
                    'payment_status_id' => PaymentStatus::Paid->value,
                ], [
                    'payment_amount' => $this->points_egp,
                    'payment_details' => json_encode([
                        "amount_cents" => number_format($this->points_egp * 100, 0, '', ''),
                        "points" => $used_points,
                        "transaction_id" => null,
                        "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                    ]),
                ]);

                while ($used_points > 0) {
                    $oldest_points = $order
                        ->user
                        ->points
                        ->where('status', 1)
                        ->where('created_at', '>=', Carbon::now()->subDays(90)->toDateTimeString())
                        ->sortBy('created_at')
                        ->first();

                    if ($oldest_points->value <= $used_points) {
                        $used_points -= $oldest_points->value;
                        $oldest_points->delete();
                    } else {
                        $oldest_points->update([
                            'value' => $oldest_points->value - $used_points
                        ]);
                        $used_points = 0;
                    }

                    $order->user->load('points');
                }
            }

            // Add Points to the user if present
            if ($total_points_after_coupon_points) {
                $order->user->points()->create([
                    'order_id' => $order->id,
                    'value' => $total_points_after_coupon_points,
                    'status' => 0
                ]);
            }

            // Create a transaction according to the order payment method
            $should_pay = $this->total - $this->wallet - $this->points_egp;

            if ($should_pay > 0) {
                $invoice->transactions()->updateOrCreate([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'payment_status_id' => PaymentStatus::Pending->value,
                ], [
                    'payment_method_id' => $this->payment_method,
                    'payment_amount' => $should_pay,
                    'payment_details' => json_encode([
                        "amount_cents" => number_format($should_pay * 100, 0, '', ''),
                        "points" => 0,
                        "transaction_id" => null,
                        "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                    ]),
                ]);
            } else {
                $invoice->transactions()->where([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'payment_status_id' => PaymentStatus::Paid->value
                ])->delete();
            }

            // Add Products and Collections to the order
            // get order's products
            $final_products = [];

            foreach ($this->best_products as $product) {
                $final_products[$product['id']] = [
                    'quantity' => $product['qty'],
                    'original_price' => $product['original_price'],
                    'price' => $product['best_price'] - $product['coupon_discount'],
                    'points' => $product['best_points'],
                    'coupon_discount' => $product['coupon_discount'],
                    'coupon_points' => $product['coupon_points'],
                ];
            };


            // get order's collections
            $final_collections = [];

            foreach ($this->best_collections as $collection) {
                $final_collections[$collection['id']] = [
                    'quantity' => $collection['qty'],
                    'original_price' => $collection['original_price'],
                    'price' => $collection['best_price'] - $collection['coupon_discount'],
                    'points' => $collection['best_points'],
                    'coupon_discount' => $collection['coupon_discount'],
                    'coupon_points' => $collection['coupon_points'],
                ];
            };

            ################### Modify Products and Collections :: Start ###################
            // Add Previous Order Amounts of Collections and Products
            // Get Products from database
            $order->products()->each(function ($product) {
                $product->quantity = $product->quantity + $product->pivot->quantity;
                $product->save();
            });

            // Get Collections from database
            $order->collections()->each(function ($collection) {
                $collection->products()->each(function ($product) use ($collection) {
                    $product->quantity += $collection->pivot->quantity * $product->pivot->quantity;
                    $product->save();
                });
            });


            // update order's products
            if (count($final_products)) {
                $order->products()->sync(
                    $final_products
                );
            } else {
                $order->products()->detach();
            }

            // update order's collections
            if (count($final_collections)) {
                $order->collections()->sync(
                    $final_collections
                );
            } else {
                $order->collections()->detach();
            }


            // Remove Order Amounts from Collections and Products
            $order->products()->each(function ($product) use (&$final_products) {
                $product->quantity = $product->quantity - $final_products[$product->id]['quantity'];
                $product->save();
            });

            $order->collections()->each(function ($collection) use (&$final_collections) {
                $products = $collection->products();

                $products->each(function ($product) use (&$collection, &$final_collections) {
                    $product->quantity = $product->quantity - ($final_collections[$collection->id]['quantity'] * $product->pivot->quantity);
                    $product->save();
                });
            });

            ################### Modify Products and Collections :: End ###################

            // Update Coupon Count
            if ($order->coupon_id != null) {
                $coupon = Coupon::find($order->coupon_id);

                $coupon->update([
                    'number' => $coupon->number != null && $coupon->number > 0 ? $coupon->number - 1 : $coupon->number,
                ]);
            }

            DB::commit();

            // redirect to done page
            Session::flash('success', __('admin/ordersPages.Order Created Successfully'));

            if ($new_order) {
                redirect()->route('admin.orders.create');
            } else {
                redirect()->route('admin.orders.new-orders');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
