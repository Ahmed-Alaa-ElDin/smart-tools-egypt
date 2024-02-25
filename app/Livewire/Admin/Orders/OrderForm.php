<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Zone;
use Livewire\Component;

class OrderForm extends Component
{
    public $make_order = false;
    public $zones;
    public $products_id = [];
    public $products_best_coupon;
    public $coupon_discount_percentage;


    public $customer,
        $default_address,
        $default_phone,
        $products,
        $coupon_id,
        $wallet,
        $points,
        $points_egp,
        $payment_method;

    public $best_products,
        $products_amounts = 0,
        $product_total_amounts = 0,
        $products_weights = 0,
        $products_total_weights = 0,
        $delivery_fees,
        $zone_id,
        $subtotal = 0.00,
        $products_base_prices = 0.00,
        $products_final_prices = 0.00,
        $products_best_prices = 0.00,
        $products_discounts = 0.00,
        $products_discounts_percentage = 0,
        $offers_discounts = 0,
        $offers_discounts_percentage = 0,
        $products_best_points = 0,
        $order_discount = 0.00,
        $order_discount_percentage = 0,
        $order_points = 0,
        $total_points = 0,
        $total = 0.00,
        $total_after_wallet = 0.00;

    public $products_after_coupon = [],
        $coupon_discount = 0.00,
        $coupon_points = 0,
        $order_best_coupon = 0.00,
        $coupon_free_shipping = 0;

    protected $rules = [
        'customer'              =>       'required',
        'default_address'       =>       'required_with:customer',
        'default_phone'         =>       'required_with:customer',
        'products'              =>       'array|min:1',
        'payment_method'        =>       'required|in:1,2,3,4',
    ];

    public function messages()
    {
        return [
            'customer.required'                 =>      __('admin/ordersPages.Please select a customer first'),
            'default_address.required_with'     =>      __('admin/ordersPages.Please select the default address'),
            'default_phone.required_with'       =>      __('admin/ordersPages.Please select the default phone number'),
            'products.min'                      =>      __('admin/ordersPages.Please select the products (at least one product)'),
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

    public function getOrderData($make_order = false)
    {
        $this->make_order = $make_order;

        $this->dispatch('getUserData')->to('admin.orders.new-order-user-part');
    }

    public function setUserData($data)
    {
        $this->customer = $data['customer'];

        $this->default_address = $data['defaultAddress'];

        $this->default_phone = $data['defaultPhone'];

        $this->dispatch('getProductsData')->to('admin.orders.new-order-products-part');
    }

    public function setProductsData($data)
    {
        $this->products = $data['products'];

        $this->dispatch('getPaymentData')->to('admin.orders.new-order-payment-part');
    }

    public function setPaymentData($data)
    {
        $this->coupon_id = $data['coupon_id'];
        $this->wallet = $data['wallet'];
        $this->points = $data['points'];
        $this->payment_method = $data['payment_method'];

        $this->validate();

        $this->calculate();

        if ($this->make_order) {
            if ($this->total_after_wallet < 0 || $this->total < 0) {
                $this->dispatch('displayOrderSummary');
            } else {
                $this->makeOrder();
            }
        } else {
            $this->dispatch('displayOrderSummary');
        }
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
            $this->getCoupon();
        } else {
            $this->products_after_coupon = [];
            $this->coupon_discount = 0.00;
            $this->coupon_points = 0;
            $this->order_best_coupon = 0.00;
            $this->coupon_free_shipping = 0;
        }

        // Total
        $this->getTotal();
    }

    // Get Best Products Data
    public function getProducts()
    {
        $products = $this->products;

        $products_id = array_keys($products);

        $this->products_id = $products_id;

        $this->best_products = getBestOfferForProducts($products_id)->map(function ($product) use ($products) {
            $product->amount = $products[$product->id]['amount'];

            return $product;
        });

        $this->products_amounts = $this->best_products->pluck('amount', 'id');

        $this->product_total_amounts = $this->products_amounts->sum() ?? 0;

        $this->products_weights = $this->best_products->map(fn ($p) => $p->free_shipping ? 0 : $p->weight * $p->amount);

        $this->products_total_weights = $this->products_weights->sum() ?? 0;
    }

    // Get Delivery Fees
    public function getDeliveryFees()
    {
        // Products Total Weight
        $products_total_weights = $this->products_total_weights ?? 0;

        // Zones Data
        $address = Address::findOrFail($this->default_address);

        $zones = Zone::with(['destinations'])
            ->where('is_active', 1)
            ->whereHas('destinations', fn ($q) => $q->where('city_id', $address->city_id))
            ->whereHas('delivery', fn ($q) => $q->where('is_active', 1))
            ->get();

        $this->zones = $zones;

        $this->validate([
            'zones' => "min:1"
        ], [
            'zones.min' => __("admin/ordersPages.There's no delivery service to this area")
        ]);

        // Get the best Delivery Cost
        $prices = $zones->map(function ($zone) use ($products_total_weights) {
            $min_charge = $zone->min_charge;
            $min_weight = $zone->min_weight;
            $kg_charge = $zone->kg_charge;

            if ($products_total_weights < $min_weight) {
                return [
                    'zone_id' => $zone->id,
                    'charge' => $min_charge
                ];
            } else {
                return [
                    'zone_id' => $zone->id,
                    'charge' => $min_charge + ($products_total_weights - $min_weight) * $kg_charge
                ];
            }
        });

        // delivery fees
        $this->delivery_fees = $prices->min('charge');

        // best zone
        $best_zone = $prices->filter(function ($price) {
            return $price['charge'] == $this->delivery_fees;
        });

        if ($best_zone->count()) {
            $this->zone_id = $best_zone->first()['zone_id'];
        } else {
            $this->zone_id = null;
        }
    }

    // Calculate Subtotal
    public function getSubTotal()
    {
        $products_amounts = $this->products_amounts;

        // get base prices
        $this->products_base_prices = $this->best_products->map(function ($product) use ($products_amounts) {
            $product_amount = $products_amounts[$product->id];

            return $product->base_price * $product_amount;
        })->sum();

        // get final prices
        $this->products_final_prices = $this->best_products->map(function ($product) use ($products_amounts) {
            $product_amount = $products_amounts[$product->id];

            return $product->final_price * $product_amount;
        })->sum();

        // get best prices
        $this->products_best_prices = $this->best_products->map(function ($product) use ($products_amounts) {
            $product_amount = $products_amounts[$product->id];

            return $product->best_price * $product_amount;
        })->sum();

        // Get products discounts value
        $this->products_discounts = $this->products_base_prices - $this->products_final_prices;

        // Get products discounts percentage
        $this->products_discounts_percentage = $this->products_base_prices > 0 ? round(($this->products_discounts / $this->products_base_prices) * 100, 0) : 0.00;

        // get discount
        $this->offers_discounts = $this->products_final_prices - $this->products_best_prices;

        // get discount percent
        $this->offers_discounts_percentage = $this->products_final_prices > 0 ? number_format(($this->offers_discounts / $this->products_final_prices) * 100) : 0;

        // get products points
        $this->products_best_points = $this->best_products->map(function ($product) use ($products_amounts) {
            $product_qty = $products_amounts[$product->id];

            return $product->best_points * $product_qty;
        })->sum();

        // get Extra discount for total order
        $order_offer = Offer::orderOffers()->first();

        if ($order_offer) {
            // Percent Discount
            if ($order_offer->type == 0 && $order_offer->value <= 100) {
                $this->order_discount = $this->products_best_prices * ($order_offer->value / 100);
                $this->order_discount_percentage = round($order_offer->value);
            }
            // Fixed Discount
            elseif ($order_offer->type == 1) {
                $this->order_discount = $this->products_best_prices >= $order_offer->value ? $order_offer->value : $this->products_best_prices;
                $this->order_discount_percentage = $this->products_best_prices > 0 ? round(($this->order_discount * 100) / $this->products_best_prices) : 0;
            }
            // Points
            elseif ($order_offer->type == 2) {
                $this->order_points = $order_offer->value;
            }
        }
    }

    // Update Order Cost after applying the Coupon
    public function getCoupon()
    {
        $products = $this->best_products;

        $products_id = $this->products_id;

        $products_amounts = $this->products_amounts;

        // Coupon Data
        $coupon = Coupon::with([
            'supercategories' => function ($q) {
                $q->with(['products']);
            },
            'categories' => function ($q) {
                $q->with(['products']);
            },
            'subcategories' => function ($q) {
                $q->with(['products']);
            },
            'brands' => function ($q) {
                $q->with(['products']);
            },
            'products',
        ])->findOrFail($this->coupon_id);

        // get free shipping
        $this->coupon_free_shipping = $coupon->free_shipping ? true : false;

        // get discount on brands
        if ($coupon->brands->count()) {
            $brands_product_from_coupon = $coupon->brands->map(function ($brand) use ($products) {
                return [
                    'products' => $products->whereIn('id', $brand->products->pluck('id')),
                    'type' => $brand->pivot->type,
                    'value' => $brand->pivot->value,
                ];
            });

            $brands_product_from_coupon->map(function ($brand) {
                foreach ($brand['products'] as $product) {

                    if ($brand['type'] == 0 && $brand['value'] < 100) {
                        $product->coupon_discount = $product->best_price * $brand['value'] / 100;
                        $product->coupon_points = 0;
                        $this->products_after_coupon[] = $product;
                    } elseif ($brand['type'] == 1) {
                        $product->coupon_discount = $brand['value'] <= $product->best_price ? $brand['value'] : $product->best_price;
                        $product->coupon_points = 0;
                        $this->products_after_coupon[] = $product;
                    } elseif ($brand['type'] == 2) {
                        $product->coupon_discount = 0.00;
                        $product->coupon_points = $brand['value'];
                        $this->products_after_coupon[] = $product;
                    }
                }
            });
        }

        // get discount on subcategories
        if ($coupon->subcategories->count()) {
            $subcategories_product_from_coupon = $coupon->subcategories->map(function ($subcategory) use ($products) {
                return [
                    'products' => $products->whereIn('id', $subcategory->products->pluck('id')),
                    'type' => $subcategory->pivot->type,
                    'value' => $subcategory->pivot->value,
                ];
            });

            $subcategories_product_from_coupon->map(function ($subcategory) {
                foreach ($subcategory['products'] as $product) {
                    if ($subcategory['type'] == 0 && $subcategory['value'] < 100) {
                        $product->coupon_discount = $product->best_price * $subcategory['value'] / 100;
                        $product->coupon_points = 0;
                        $this->products_after_coupon[] = $product;
                    } elseif ($subcategory['type'] == 1) {
                        $product->coupon_discount = $subcategory['value'] <= $product->best_price ? $subcategory['value'] : $product->best_price;
                        $product->coupon_points = 0;
                        $this->products_after_coupon[] = $product;
                    } elseif ($subcategory['type'] == 2) {
                        $product->coupon_discount = 0.00;
                        $product->coupon_points = $subcategory['value'];
                        $this->products_after_coupon[] = $product;
                    }
                }
            });
        }

        // get discount on categories
        if ($coupon->categories->count()) {
            $categories_product_from_coupon = $coupon->categories->map(function ($category) use ($products) {
                return [
                    'products' => $products->whereIn('id', $category->products->pluck('id')),
                    'type' => $category->pivot->type,
                    'value' => $category->pivot->value,
                ];
            });

            $categories_product_from_coupon->map(function ($category) {
                foreach ($category['products'] as $product) {
                    if ($category['type'] == 0 && $category['value'] < 100) {
                        $product->coupon_discount = $product->best_price * $category['value'] / 100;
                        $product->coupon_points = 0;
                        $this->products_after_coupon[] = $product;
                    } elseif ($category['type'] == 1) {
                        $product->coupon_discount = $category['value'] <= $product->best_price ? $category['value'] : $product->best_price;
                        $product->coupon_points = 0;
                        $this->products_after_coupon[] = $product;
                    } elseif ($category['type'] == 2) {
                        $product->coupon_discount = 0.00;
                        $product->coupon_points = $category['value'];
                        $this->products_after_coupon[] = $product;
                    }
                }
            });
        }

        // get discount on supercategories
        if ($coupon->supercategories->count()) {
            $supercategories_product_from_coupon = $coupon->supercategories->map(function ($supercategory) use ($products) {
                return [
                    'products' => $products->whereIn('id', $supercategory->products->pluck('id')),
                    'type' => $supercategory->pivot->type,
                    'value' => $supercategory->pivot->value,
                ];
            });

            $supercategories_product_from_coupon->map(function ($supercategory) {
                foreach ($supercategory['products'] as $product) {

                    if ($supercategory['type'] == 0 && $supercategory['value'] < 100) {
                        $product->coupon_discount = $product->best_price * $supercategory['value'] / 100;
                        $product->coupon_points = 0;
                        $this->products_after_coupon[] = $product;
                    } elseif ($supercategory['type'] == 1) {
                        $product->coupon_discount = $supercategory['value'] <= $product->best_price ? $supercategory['value'] : $product->best_price;
                        $product->coupon_points = 0;
                        $this->products_after_coupon[] = $product;
                    } elseif ($supercategory['type'] == 2) {
                        $product->coupon_discount = 0.00;
                        $product->coupon_points = $supercategory['value'];
                        $this->products_after_coupon[] = $product;
                    }
                }
            });
        }

        // get discount on products
        if ($coupon->products->count()) {
            $products_product_from_coupon = $coupon->products->map(function ($product) use ($products_id, $products) {
                if (in_array($product->id, $products_id)) {
                    return [
                        'product' => $products->find($product->id),
                        'type' => $product->pivot->type,
                        'value' => $product->pivot->value,
                    ];
                }
            })->whereNotNull();

            $products_product_from_coupon->map(function ($product) {
                if ($product['type'] == 0 && $product['value'] < 100) {
                    $product['product']->coupon_discount = $product['product']->best_price * $product['value'] / 100;
                    $product['product']->coupon_points = 0;
                    $this->products_after_coupon[] = $product['product'];
                } elseif ($product['type'] == 1) {
                    $product['product']->coupon_discount = $product['value'] <= $product['product']->best_price ? $product['value'] : $product['product']->best_price;
                    $product['product']->coupon_points = 0;
                    $this->products_after_coupon[] = $product['product'];
                } elseif ($product['type'] == 2) {
                    $product['product']->coupon_discount = 0.00;
                    $product['product']->coupon_points = $product['value'];
                    $this->products_after_coupon[] = $product['product'];
                }
            });
        }

        // Final Products After Coupon Application
        $this->products_best_coupon = collect($this->products_after_coupon)
            ->groupBy('id')
            ->map(function ($products) use ($products_amounts) {
                $max_discount = $products->max('coupon_discount');
                $max_points = $products->max('coupon_points');
                $products_amount = $products_amounts[$products->first()['id']];
                return [
                    'product_id' => $products->first()['id'],
                    'qty' => $products_amount,
                    'coupon_discount' => $max_discount,
                    'total_discount' =>  $products_amount * $max_discount,
                    'coupon_points' => $max_points,
                    'total_points' => $products_amount * $max_points,
                ];
            });

        // Total Coupon Discount After Products
        $this->coupon_discount = $this->products_best_coupon->sum('total_discount');
        // Total Coupon Points After Products
        $this->coupon_points = $this->products_best_coupon->sum('total_points');

        // get discount on order
        if ($coupon->on_orders) {
            // percentage discount
            if ($coupon->type == 0 && $coupon->value < 100) {
                $discount_order_from_coupon = ($this->total - $this->coupon_discount) * $coupon->value / 100;
                $this->order_best_coupon = [
                    'discount' => $discount_order_from_coupon,
                    'points' => 0
                ];
                $this->coupon_discount += $discount_order_from_coupon;
            }
            // fixed discount
            elseif ($coupon->type == 1) {
                $discount_order_from_coupon = $coupon->value <= ($this->total - $this->coupon_discount) ? $coupon->value : ($this->total - $this->coupon_discount);
                $this->order_best_coupon = [
                    'discount' => $discount_order_from_coupon,
                    'points' => 0
                ];
                $this->coupon_discount += $discount_order_from_coupon <= ($this->total - $this->coupon_discount) ? $discount_order_from_coupon : ($this->total - $this->coupon_discount);
            }
            // points
            elseif ($coupon->type == 2) {
                $points_from_coupon = $coupon->value;
                $this->order_best_coupon = [
                    'discount' => 0.00,
                    'points' => $points_from_coupon
                ];
                $this->coupon_points += $points_from_coupon;
            }
        }

        $this->coupon_discount_percentage = $this->products_best_prices > 0 ? round($this->coupon_discount / $this->products_best_prices * 100) : 0;
    }

    // Calculate Total Cost
    public function getTotal()
    {
        $this->total_points = $this->products_best_points + $this->order_points + $this->coupon_points;

        $this->subtotal = $this->products_best_prices -  $this->coupon_discount ?? 0.00;

        $this->total = is_null($this->delivery_fees) || $this->delivery_fees == 0 || $this->coupon_free_shipping ? $this->products_best_prices - $this->order_discount - $this->coupon_discount : $this->products_best_prices - $this->order_discount - $this->coupon_discount + $this->delivery_fees;

        $this->points_egp = $this->points * config('settings.points_conversion_rate');

        $this->total_after_wallet = $this->total - $this->wallet - $this->points_egp;
    }

    // Make Order
    public function makeOrder()
    {
        $non_default_phones =  array_map(fn ($phone) => $phone['phone'], array_filter($this->customer['phones'], fn ($phone) => $phone['default'] == 0));

        // dd($this->product_total_amounts);

        try {
            Order::updateOrCreate([
                'status_id' => 1,
                'user_id'   => $this->customer_id,
            ], [
                'address_id' => $this->address_id,
                'phone1' => $this->default_phone,
                'phone2' => count($non_default_phones) ? implode("-", $non_default_phones) : null,
                'package_type' => 'parcel',
                'package_desc' => 'قابل للكسر',
                'num_of_items' => $this->product_total_amounts,
                'allow_opening' => 1,
                'zone_id' => $this->zone_id,
                'coupon_id' => $this->coupon_id,
                'coupon_order_discount',
                'coupon_order_points',
                'coupon_products_discount',
                'coupon_products_points',
                'subtotal_base',
                'subtotal_final',
                'total',
                'should_pay',
                'should_get',
                'used_points',
                'used_balance',
                'gift_points',
                'delivery_fees',
                'total_weight',
                'payment_method',
                'tracking_number',
                'order_delivery_id',
                'notes',
                'delivered_at',
                'old_order_id',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
