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
        } else {
            $this->products_final_prices = "0.00";
            $this->products_best_prices = "0.00";
            $this->discount = "0.00";
            $this->discount_percent = "0";
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
