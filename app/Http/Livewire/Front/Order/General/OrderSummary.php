<?php

namespace App\Http\Livewire\Front\Order\General;

use App\Models\Offer;
use App\Models\Zone;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class OrderSummary extends Component
{
    public $step;
    public $items;
    public $items_quantities = [];
    public $items_weights = 0.00;
    public $items_base_prices = 0.00;
    public $items_final_prices = 0.00;
    public $items_discounts = 0.00;
    public $items_discounts_percentage = 0;
    public $items_best_prices = 0.00;
    public $offers_discounts = 0.00;
    public $offers_discounts_percentage = 0;
    public $items_best_points = 0;
    public $best_zone_id;
    public $delivery_fees = 0.00;
    public $total = 0.00;
    public $order_discount = 0.00;
    public $order_discount_percentage = 0;
    public $order_points = 0;
    public $total_points = 0;
    public $coupon_id;
    public $coupon_discount = 0.00;
    public $coupon_discount_percentage = 0;
    public $coupon_points = 0;
    public $coupon_free_shipping = false;
    public $items_best_coupon = [];
    public $order_best_coupon = [
        'discount' => 0.00,
        'points' => 0
    ];

    protected $listeners = [
        'cartUpdated' => 'getProducts',
        'AddressUpdated' => 'getDeliveryPrice',
        'couponApplied',
        'getOrderFinalPrice',
    ];

    ############# Render :: Start #############
    public function render()
    {
        $items = $this->items;
        $new_items=[];

        Cart::instance('cart')->content()->map(function ($item) use ($items,&$new_items) {
            $old_item = array_filter(
                $items,
                function ($item_data) use ($item) {
                    if ($item_data['id'] == $item->id && $item_data['type'] == $item->options->type) {
                        return $item;
                    }
                }
            )[0];

            $old_item ['order_qty'] = $item->qty;

            $new_items[]=$old_item;
        });

        dd($items);

        $items_quantities = [];

        Cart::instance('cart')->content()->map(function ($item) use (&$items_quantities) {
            $items_quantities[] = [
                'id' => $item->id,
                'type' => $item->options->type,
                'quantity' => $item->qty
            ];
        });

        $this->items_quantities = $items_quantities;

        // dd(Cart::instance('cart')->count(), $this->items_quantities);
        if ($this->step == 3) {
            $this->getItems();
        }

        if ($this->step > 1) {
            $this->getDeliveryPrice();
        }

        if (Cart::instance('cart')->count()) {
            // get base prices
            $this->items_base_prices = array_sum(array_map(function ($item) use ($items_quantities) {
                $item_quantity = 0;

                array_map(function ($item_data) use ($item, &$item_quantity) {
                    if ($item_data['id'] == $item['id'] && $item_data['type'] == $item['type']) {
                        $item_quantity = $item['quantity'];
                    }
                }, $items_quantities);
                return $item['base_price'] * $item_quantity;
            }, $this->items));

            dd($this->items_base_prices);
            // $this->items_base_prices = $this->items->map(function ($item) use ($items_quantities) {
            //     $item_qty = $items_quantities[$item->id];

            //     return $item->base_price * $item_qty;
            // })->sum();

            // get final prices
            $this->items_final_prices = $this->items->map(function ($item) use ($items_quantities) {
                $item_qty = $items_quantities[$item->id];

                return $item->final_price * $item_qty;
            })->sum();

            // get best prices
            $this->items_best_prices = $this->items->map(function ($item) use ($items_quantities) {
                $item_qty = $items_quantities[$item->id];

                return $item->best_price * $item_qty;
            })->sum();

            // Get items discounts value
            $this->items_discounts = $this->items_base_prices - $this->items_final_prices;

            // Get items discounts percentage
            $this->items_discounts_percentage = $this->items_base_prices > 0 ? round(($this->items_discounts / $this->items_base_prices) * 100, 0) : 0.00;

            // get discount
            $this->offers_discounts = $this->items_final_prices - $this->items_best_prices;

            // get discount percent
            $this->offers_discounts_percentage = $this->items_final_prices > 0 ? number_format(($this->offers_discounts / $this->items_final_prices) * 100) : 0;

            // get items points
            $this->items_best_points = $this->items->map(function ($item) use ($items_quantities) {
                $item_qty = $items_quantities[$item->id];

                return $item->best_points * $item_qty;
            })->sum();

            // get Extra discount for total order
            $order_offer = Offer::orderOffers()->first();

            if ($order_offer) {
                // Percent Discount
                if ($order_offer->type == 0 && $order_offer->value <= 100) {
                    $this->order_discount = $this->items_best_prices * ($order_offer->value / 100);
                    $this->order_discount_percentage = round($order_offer->value);
                }
                // Fixed Discount
                elseif ($order_offer->type == 1) {
                    $this->order_discount = $this->items_best_prices >= $order_offer->value ? $order_offer->value : $this->items_best_prices;
                    $this->order_discount_percentage = round(($this->order_discount * 100) / $this->items_best_prices);
                }
                // Points
                elseif ($order_offer->type == 2) {
                    $this->order_points = $order_offer->value;
                }
            }

            // get total points
            $this->total_points = $this->items_best_points + $this->order_points;

            $this->total = !is_numeric($this->delivery_fees) || $this->delivery_fees == 0 || $this->coupon_free_shipping ? $this->items_best_prices - $this->order_discount - $this->coupon_discount : $this->items_best_prices - $this->order_discount - $this->coupon_discount + $this->delivery_fees;
        }

        return view('livewire.front.order.general.order-summary');
    }
    ############# Render :: End #############

    ############## Get Products :: Start ##############
    public function getProducts()
    {
        $items_id = Cart::instance('cart')->content()->pluck('id');

        $this->items = getBestOfferForProducts($items_id);
    }
    ############## Get Products :: End ##############

    ############## Get Delivery Price :: Start ##############
    public function getDeliveryPrice()
    {
        $items_quantities = $this->items_quantities;

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

                // get items weights
                $items_weights = $this->items->map(function ($item) use ($items_quantities) {
                    if (!$item->free_shipping) {
                        $item_qty = $items_quantities[$item->id];

                        return $item->weight * $item_qty;
                    }
                })->sum();

                $this->items_weights = $items_weights;

                // Get the best Delivery Cost
                $prices = $zones->map(function ($zone) use ($items_weights) {
                    $min_charge = $zone->min_charge;
                    $min_weight = $zone->min_weight;
                    $kg_charge = $zone->kg_charge;

                    if ($items_weights < $min_weight) {
                        return [
                            'zone_id' => $zone->id,
                            'charge' => $min_charge
                        ];
                    } else {
                        return [
                            'zone_id' => $zone->id,
                            'charge' => $min_charge + ($items_weights - $min_weight) * $kg_charge
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
    public function couponApplied($coupon_id, $coupon_discount, $coupon_discount_percentage, $coupon_points, $coupon_free_shipping, $items_best_coupon, $order_best_coupon)
    {
        $this->coupon_id = $coupon_id;
        $this->coupon_discount = $coupon_discount;
        $this->coupon_discount_percentage = $coupon_discount_percentage;
        $this->coupon_points = $coupon_points;
        $this->coupon_free_shipping = $coupon_free_shipping;
        $this->items_best_coupon = $items_best_coupon;
        $this->order_best_coupon = $order_best_coupon;

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
                'items' => $this->items,
                'subtotal_base' => $this->items_final_prices ?? 0.00,
                'subtotal_final' => $this->items_best_prices -  $this->coupon_discount ?? 0.00,
                'total' => $this->total,
                'delivery_fees' => $this->coupon_free_shipping ? 0.00 : $this->delivery_fees,
                'coupon_id' => $this->coupon_id ?? null,
                'coupon_discount' => $this->coupon_discount,
                'coupon_points' => $this->coupon_points,
                'items_best_coupon' => $this->items_best_coupon ?? [],
                'order_best_coupon' => $this->order_best_coupon,
                'zone_id' => $this->best_zone_id ?? null,
                'weight' => $this->items_weights ?? 1,
                'gift_points' => $this->coupon_points + $this->total_points,
            ]
        );
    }
}
