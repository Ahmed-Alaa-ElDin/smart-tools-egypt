<?php

namespace App\Http\Livewire\Front\General;

use Gloudemans\Shoppingcart\Facades\Cart as FacadesCart;
use Livewire\Component;

class Cart extends Component
{
    protected $listeners = ['product_added_to_cart' => 'render'];

    public function render()
    {
        $this->cart = FacadesCart::instance('cart')->content();

        return view('livewire.front.general.cart');
    }
}
