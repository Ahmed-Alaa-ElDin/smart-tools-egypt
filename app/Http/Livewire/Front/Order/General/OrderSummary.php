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
            $this->products_final_prices = array_sum(array_map(function ($product) {
                if (!$product['under_reviewing']) {
                    $product_qty = Cart::search(function ($cartItem, $rowId) use ($product) {
                        return $cartItem->id === $product['id'];
                    })->first()->qty;

                    return $product['final_price'] * $product_qty;
                }
            }, $this->products));

            // get best prices
            $this->products_best_prices = array_sum(array_map(function ($product) {
                if (!$product['under_reviewing']) {
                    $product_qty = Cart::search(function ($cartItem, $rowId) use ($product) {
                        return $cartItem->id === $product['id'];
                    })->first()->qty;

                    return $product['best_price'] * $product_qty;
                }
            }, $this->products));

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
        // dd($this->products, $this->products_final_prices, $this->products_best_prices, $this->discount, $this->discount_percent, explode('.', $this->discount));

        return view('livewire.front.order.general.order-summary');
    }

    ############## Get Products :: Start ##############
    public function getProducts()
    {
        $products_id = Cart::instance('cart')->content()->pluck('id');

        $products = [];

        foreach ($products_id as $product_id) {
            $products[] = getBestOffer($product_id)->toArray();
        }

        $this->products = $products;
    }
    ############## Get Products :: End ##############
}
