<?php

namespace App\Http\Livewire\Front\Order\General;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderProductsList extends Component
{
    public $products;
    public $step;

    protected $listeners = [
        'cartUpdated' => 'getProducts',
    ];

    public function render()
    {
        return view('livewire.front.order.general.order-products-list');
    }

    ############## Get Products :: Start ##############
    public function getProducts()
    {
        $products_id = Cart::instance('cart')->content()->pluck('id');

        $products = [];

        $products = getBestOfferForProducts($products_id);

        $this->products = $products;
    }
    ############## Get Products :: End ##############

    ############## Remove Products from Cart :: Start ##############
    public function removeFromCart($product_id)
    {
        Cart::instance('cart')->search(function ($cartItem, $rowId) use ($product_id) {
            return $cartItem->id === $product_id;
        })->each(function ($cartItem, $rowId) {
            Cart::instance('cart')->remove($rowId);
        });

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }

        ############ Emit Sweet Alert :: Start ############
        $this->dispatchBrowserEvent('swalDone', [
            "text" => __('front/homePage.Product Removed From Your Cart Successfully'),
            'icon' => 'success'
        ]);
        ############ Emit Sweet Alert :: End ############

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove Products from Cart :: End ##############


}
