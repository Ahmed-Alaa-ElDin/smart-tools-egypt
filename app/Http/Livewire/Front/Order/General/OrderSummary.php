<?php

namespace App\Http\Livewire\Front\Order\General;

use App\Models\Offer;
use App\Models\Zone;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class OrderSummary extends Component
{
    public $products_base_prices = 0.00;
    public $products_final_prices = 0.00;
    public $products_discounts = 0.00;
    public $products_discounts_percentage = 0;
    public $products_best_prices = 0.00;
    public $offers_discounts = 0.00;
    public $offers_discounts_percentage = 0;
    public $products_best_points = 0;
    public $delivery_fees = 0.00;
    public $total = 0.00;
    public $order_discount = 0.00;
    public $order_discount_percentage = 0;
    public $order_points = 0;
    public $total_points = 0;
    public $products;
    public $products_quantities = [];
    public $products_weights = 0.00;
    public $step;
    public $best_zone_id;
    public $coupon_id;
    public $coupon_discount = 0.00;
    public $coupon_discount_percentage = 0;
    public $coupon_points = 0;
    public $coupon_free_shipping = false;

    protected $listeners = [
        'cartUpdated' => 'getProducts',
        'AddressUpdated' => 'getDeliveryPrice',
        'couponApplied',
        'getOrderFinalPrice',
    ];

    ############# Render :: Start #############
    public function render()
    {
        $products_quantities = Cart::instance('cart')->content()->pluck('qty', 'id')->toArray();
        $this->products_quantities = $products_quantities;

        if ($this->step == 3) {
            $this->getProducts();
        }

        if ($this->step > 1) {
            $this->getDeliveryPrice();
        }

        if (Cart::instance('cart')->count()) {
            // get base prices
            $this->products_base_prices = $this->products->map(function ($product) use ($products_quantities) {
                $product_qty = $products_quantities[$product->id];

                return $product->base_price * $product_qty;
            })->sum();

            // get final prices
            $this->products_final_prices = $this->products->map(function ($product) use ($products_quantities) {
                $product_qty = $products_quantities[$product->id];

                return $product->final_price * $product_qty;
            })->sum();

            // get best prices
            $this->products_best_prices = $this->products->map(function ($product) use ($products_quantities) {
                $product_qty = $products_quantities[$product->id];

                return $product->best_price * $product_qty;
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
            $this->products_best_points = $this->products->map(function ($product) use ($products_quantities) {
                $product_qty = $products_quantities[$product->id];

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
                    $this->order_discount_percentage = round(($this->order_discount * 100) / $this->products_best_prices);
                }
                // Points
                elseif ($order_offer->type == 2) {
                    $this->order_points = $order_offer->value;
                }
            }

            // get total points
            $this->total_points = $this->products_best_points + $this->order_points + $this->coupon_points;

            $this->total = !is_numeric($this->delivery_fees) || $this->delivery_fees == 0 || $this->coupon_free_shipping ? $this->products_best_prices - $this->order_discount - $this->coupon_discount : $this->products_best_prices - $this->order_discount - $this->coupon_discount + $this->delivery_fees;
        }

        return view('livewire.front.order.general.order-summary');
    }
    ############# Render :: End #############

    ############## Get Products :: Start ##############
    public function getProducts()
    {
        $products_id = Cart::instance('cart')->content()->pluck('id');

        $this->products = getBestOfferForProducts($products_id);
    }
    ############## Get Products :: End ##############

    ############## Get Delivery Price :: Start ##############
    public function getDeliveryPrice()
    {
        $products_quantities = $this->products_quantities;

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
                $products_weights = $this->products->map(function ($product) use ($products_quantities) {
                    if (!$product->free_shipping) {
                        $product_qty = $products_quantities[$product->id];

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

                $this->delivery_fees = $prices->min('charge');

                $best_zone = $prices->filter(function ($price) {
                    return $price['charge'] == $this->delivery_fees;
                });

                if ($best_zone->count()) {
                    $this->best_zone_id = $best_zone->first()['zone_id'];
                } else {
                    $this->best_zone_id = null;
                }

                if ($this->delivery_fees == null) {
                    $this->delivery_fees = 'no delivery';
                    $this->city_name = $address->city->name;
                }
            } else {
                $this->delivery_fees = 'select default address';
            }
        }
    }
    ############## Get Delivery Price :: End ##############

    ############## Get Coupon Data :: Start ##############
    public function couponApplied($coupon_id, $coupon_discount,$coupon_discount_percentage, $coupon_points, $coupon_free_shipping)
    {
        $this->coupon_id = $coupon_id;
        $this->coupon_discount = $coupon_discount;
        $this->coupon_discount_percentage = $coupon_discount_percentage;
        $this->coupon_points = $coupon_points;
        $this->coupon_free_shipping = $coupon_free_shipping;
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
                'subtotal_final' => $this->products_best_prices ?? 0,
                'total' => $this->total,
                'delivery_fees' => $this->coupon_free_shipping ? 0.00 : $this->delivery_fees,
                'coupon_id' => $this->coupon_id ?? null,
                'coupon_discount' => $this->coupon_discount ?? 0.00,
                'coupon_points' => $this->coupon_points ?? 0,
                'zone_id' => $this->best_zone_id ?? null,
                'weight' => $this->products_weights ?? 1,
                'gift_points' => $this->coupon_points + $this->total_points,
            ]
        );
    }
}
