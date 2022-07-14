<?php

namespace App\Http\Livewire\Front\Order\General;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class OrderSummary extends Component
{
    public $products_final_prices = "0.00",
        $products_best_prices = "0.00",
        $discount = "0.00",
        $discount_percent = "0",
        $order_discount = "0.00",
        $free_shipping = false,
        $products;


    protected $listeners = [
        'cartUpdated' => 'getProducts',
    ];

    public function render()
    {
        if (Cart::instance('cart')->count() > 0) {
            // get final prices
            $this->products_final_prices = $this->products->map(function ($product) {
                if (!$product->under_reviewing) {
                    $product_qty = Cart::search(function ($cartItem, $rowId) use ($product) {
                        return $cartItem->id === $product->id;
                    })->first()->qty;

                    return $product->final_price * $product_qty;
                }
            })->sum();

            // get best prices
            $this->products_best_prices = $this->products->map(function ($product) {
                if (!$product->under_reviewing) {
                    $product_qty = Cart::search(function ($cartItem, $rowId) use ($product) {
                        return $cartItem->id === $product->id;
                    })->first()->qty;

                    return $product->best_price * $product_qty;
                }
            })->sum();

            // get discount
            $this->discount = $this->products_final_prices - $this->products_best_prices;

            // get discount percent
            if ($this->products_final_prices) {
                $this->discount_percent = number_format(($this->discount / $this->products_final_prices) * 100);
            }

            // get Extra discount for total order
            $discount_orders = $this->products->map(function ($product) {
                if (!$product->under_reviewing) {
                    return $product->offers->map(function ($offer) {
                        if ($offer->on_orders && ($offer->number || $offer->number == null)) {
                            return [
                                'type' => $offer->type,
                                'value' => $offer->value,
                            ];
                        }
                    });
                }
            })->flatten(1)->whereNotNull()->toArray();

            // get discount for total order after extra discount from order's offers
            $best_price_from_orders = [];
            $order_discount = [];

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
            }

            $best_price_from_orders = $best_price_from_orders ? min($best_price_from_orders) : 0;

            $this->best_price_from_orders = $best_price_from_orders;

            $this->order_discount = $order_discount ? max($order_discount) : 0;
            $this->order_discount_percent = $this->products_final_prices ? number_format(($this->order_discount / $this->products_final_prices) * 100) : 0;

            $this->products_best_prices -= $this->order_discount;

            // get free shipping from products
            $free_shipping_products = !$this->products->map(function ($product) {
                if (!$product->under_reviewing) {
                    return $product->free_shipping;
                }
            })->contains(0);

            // get free shipping from offers
            $free_shipping_offers = $this->products->map(function ($product) {
                if (!$product->under_reviewing) {
                    return $product->offers->map(function ($offer) {
                        return $offer->free_shipping;
                    });
                }
            })->flatten()->contains(1);

            // get free shipping
            $this->free_shipping = $free_shipping_products || $free_shipping_offers;
        } else {
            $this->products_final_prices = 0.00;
            $this->products_best_prices = 0.00;
            $this->discount = 0.00;
            $this->discount_percent = 0;
            $this->order_discount = 0.00;
        }

        return view('livewire.front.order.general.order-summary');
    }

    ############## Get Products :: Start ##############
    public function getProducts()
    {
        $products_id = Cart::instance('cart')->content()->pluck('id');

        $products = collect([]);

        $products = getBestOfferForProducts($products_id);

        $this->products = $products;
    }
    ############## Get Products :: End ##############
}
