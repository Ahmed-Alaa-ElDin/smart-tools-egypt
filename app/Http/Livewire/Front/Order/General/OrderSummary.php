<?php

namespace App\Http\Livewire\Front\Order\General;

use App\Models\Zone;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class OrderSummary extends Component
{
    public $products_final_prices = 0.00;
    public $products_best_prices = 0.00;
    public $discount = 0.00;
    public $discount_percent = 0;
    public $order_discount = 0.00;
    public $order_points = 0;
    public $free_shipping = false;
    public $products;
    public $products_weights;
    public $step;
    public $delivery_price;
    public $best_zone_id;
    public $points = 0, $total_points = 0;
    public $coupon_id, $coupon_price, $coupon_points, $coupon_shipping;


    protected $listeners = [
        'cartUpdated' => 'getProducts',
        'AddressUpdated' => 'getDeliveryPrice',
        'couponApplied',
        'getOrderFinalPrice',
    ];

    ############# Mount :: Start #############
    public function mount()
    {
    }
    ############# Mount :: End #############

    ############# Render :: Start #############
    public function render()
    {
        if ($this->step == 3) {
            $this->getProducts();
        }

        $this->getDeliveryPrice();

        if (Cart::instance('cart')->count()) {
            // get final prices
            $this->products_final_prices = $this->products->map(function ($product) {
                $product_qty = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($product) {
                    return $cartItem->id === $product->id;
                })->first()->qty;

                return $product->final_price * $product_qty;
            })->sum();

            // get best prices
            $this->products_best_prices = $this->products->map(function ($product) {
                $product_qty = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($product) {
                    return $cartItem->id === $product->id;
                })->first()->qty;

                return $product->best_price * $product_qty;
            })->sum();

            // get discount
            $this->discount = $this->products_final_prices - $this->products_best_prices;

            // get discount percent
            if ($this->products_final_prices) {
                $this->discount_percent = number_format(($this->discount / $this->products_final_prices) * 100);
            }

            // get products points
            $this->points = $this->products->map(function ($product) {
                $product_qty = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($product) {
                    return $cartItem->id === $product->id;
                })->first()->qty;

                return $product->best_points * $product_qty;
            })->sum();

            // get Extra discount for total order
            $discount_orders = $this->products->map(function ($product) {
                return $product->offers->map(function ($offer) {
                    if ($offer->on_orders && ($offer->number || $offer->number == null)) {
                        return [
                            'type' => $offer->type,
                            'value' => $offer->value,
                        ];
                    }
                });
            })->flatten(1)->whereNotNull()->toArray();

            // get discount for total order after extra discount from order's offers
            $best_price_from_orders = [];
            $order_discount = [];
            $orders_points = [];

            foreach ($discount_orders as $discount_order) {
                // Percent Discount
                if ($discount_order['type'] == 0) {
                    if ($this->products_best_prices - ($this->products_final_prices * ($discount_order['value'] / 100)) > 0) {
                        $best_price_from_orders[] = $this->products_final_prices - ($this->products_final_prices * ($discount_order['value'] / 100));
                        $order_discount[] = $this->products_final_prices * ($discount_order['value'] / 100);
                    } else {
                        $best_price_from_orders[] = $this->products_best_prices;
                        $order_discount[] = $this->products_best_prices;
                    }
                }
                // Fixed Discount
                elseif ($discount_order['type'] == 1) {
                    if ($this->products_best_prices - $discount_order['value'] > 0) {
                        $best_price_from_orders[] = $this->products_final_prices - $discount_order['value'];
                        $order_discount[] = $discount_order['value'];
                    } else {
                        $best_price_from_orders[] = $this->products_best_prices;
                        $order_discount[] = $this->products_best_prices;
                    }
                }
                // Points
                elseif ($discount_order['type'] == 2) {
                    $orders_points[] = $discount_order['value'];
                }
            }

            $best_price_from_orders = $best_price_from_orders ? min($best_price_from_orders) : 0;

            $this->best_price_from_orders = $best_price_from_orders;

            $this->order_discount = $order_discount ? max($order_discount) : 0;

            $this->order_discount_percent = $this->products_final_prices ? number_format(($this->order_discount / $this->products_final_prices) * 100) : 0;

            $this->products_best_prices -= $this->order_discount;

            $this->order_points = count($orders_points) ? number_format(max($orders_points), 0) : 0;

            $this->total_points = $this->points + $this->order_points;

            // get free shipping from products
            $free_shipping_products = !$this->products->map(function ($product) {
                return $product->free_shipping;
            })->contains(0);

            $this->free_shipping = $free_shipping_products;

            $this->products_best_prices = is_numeric($this->delivery_price) && !$this->free_shipping ? $this->products_best_prices + $this->delivery_price : $this->products_best_prices;
        } else {
            $this->products_final_prices = 0.00;
            $this->products_best_prices = 0.00;
            $this->discount = 0.00;
            $this->discount_percent = 0;
            $this->order_discount = 0.00;
        }

        return view('livewire.front.order.general.order-summary');
    }
    ############# Render :: End #############

    ############## Get Products :: Start ##############
    public function getProducts()
    {
        $products_id = Cart::instance('cart')->content()->pluck('id');

        $products = collect([]);

        $products = getBestOfferForProducts($products_id);

        $this->products = $products;
    }
    ############## Get Products :: End ##############

    ############## Get Delivery Price :: Start ##############
    public function getDeliveryPrice()
    {
        if (auth()->check()) {
            $address = auth()->user()->addresses->where('default', 1)->first();

            if ($address) {
                // Get City Id
                $city_id = $address->city->id;

                // Get Destinations and Zones for the city
                $zones = Zone::with(['destinations'])
                    ->where('is_active', 1)
                    ->whereHas('destinations', fn ($q) => $q->where('city_id', $city_id))
                    ->whereHas('delivery', fn ($q) => $q->where('is_active', 1))
                    ->get();

                // get products weights
                $products_weights = (int)$this->products->map(function ($product) {
                    if (!$product->free_shipping) {
                        $product_qty = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($product) {
                            return $cartItem->id === $product->id;
                        })->first()->qty;

                        return $product->weight * $product_qty;
                    }
                })->sum();

                $this->products_weights = $products_weights;

                // Get the best Delivery Cost
                $prices = $zones->map(function ($zone) use ($products_weights) {
                    $min_charge = $zone->min_charge;
                    $min_weight = $zone->min_weight;
                    $kg_charge = $zone->kg_charge;

                    if ($products_weights < $min_weight) {
                        return [
                            'zone_id' => $zone->id,
                            'charge' => $min_charge
                        ];
                    } else {
                        return [
                            'zone_id' => $zone->id,
                            'charge' => $min_charge + ($products_weights - $min_weight) * $kg_charge
                        ];
                    }
                });

                $this->delivery_price = $prices->min('charge');

                $best_zone = $prices->filter(function ($price) {
                    return $price['charge'] == $this->delivery_price;
                });

                if ($best_zone->count()) {
                    $this->best_zone_id = $best_zone->first()['zone_id'];
                } else {
                    $this->best_zone_id = null;
                }

                if ($this->delivery_price == null) {
                    $this->delivery_price = 'no delivery';
                    $this->city_name = $address->city->name;
                }
            } else {
                $this->delivery_price = 'select default address';
            }
        }
    }
    ############## Get Delivery Price :: End ##############

    ############## Get Coupon Data :: Start ##############
    public function couponApplied($coupon_id, $coupon_price, $coupon_points, $coupon_shipping)
    {
        $this->coupon_id = $coupon_id;
        $this->coupon_price = $coupon_shipping ? $coupon_price - $this->delivery_price : $coupon_price;
        $this->coupon_points = $coupon_points;
        $this->coupon_shipping = $coupon_shipping;
        $this->getProducts();
        $this->getDeliveryPrice();
    }
    ############## Get Coupon Data :: End ##############

    ############## Get Order Final Price :: Start ##############
    public function getOrderFinalPrice()
    {
        $this->getProducts();
        $this->getDeliveryPrice();

        $this->emit(
            'setOrderFinalPrice',
            [
                'products' => $this->products,
                'subtotal_base' => $this->products_final_prices ?? 0,
                'subtotal_final' => $this->coupon_price && $this->coupon_shipping ? $this->coupon_price : ($this->coupon_price && !$this->coupon_shipping ? $this->coupon_price - $this->delivery_price : ($this->products_best_prices - $this->delivery_price ?? 0)),
                'delivery_fees' => $this->coupon_shipping == true ? 0 : ($this->free_shipping == true ? 0 : ($this->delivery_price ?? 0)),
                'coupon_id' => $this->coupon_id ?? null,
                'zone_id' => $this->best_zone_id ?? null,
                'weight' => $this->products_weights ?? 1,
                'gift_points' => $this->coupon_points ?? ($this->total_points ?? 0),
            ]
        );
    }
}
