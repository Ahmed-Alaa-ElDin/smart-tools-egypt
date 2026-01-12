<?php

namespace App\Livewire\Front\Cart;

use Livewire\Attributes\On;
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;

class MobileCart extends Component
{
    public $cartCount;

    public function mount()
    {
        $this->cartCount = Cart::instance('cart')->count();
    }

    #[On("cartUpdated")]
    public function updateCartCount()
    {
        $this->cartCount = Cart::instance('cart')->count();
    }

    public function render()
    {
        return view('livewire.front.cart.mobile-cart');
    }
}
