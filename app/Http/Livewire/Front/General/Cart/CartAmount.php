<?php

namespace App\Http\Livewire\Front\General\Cart;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartAmount extends Component
{
    public $product_id;

    protected $listeners = [
        'cart_updated' => 'render',
    ];

    public function render()
    {
        $cartProduct = Cart::instance('cart')
            ->search(function ($cartItem, $rowId) {
                return $cartItem->id === $this->product_id;
            })
            ->first();
        $this->cartAmount = $cartProduct ? $cartProduct->qty : 0;

        return view('livewire.front.general.cart.cart-amount', compact('cartProduct'));
    }

    ############## Add One Item To Cart :: Start ##############
    public function addOneToCart($rowId, $quantity)
    {
        Cart::instance('cart')->update($rowId, $quantity);

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cart_updated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Add One Item To Cart :: End ##############

    ############## Remove From Cart :: Start ##############
    public function removeOneFromCart($rowId, $quantity)
    {
        Cart::instance('cart')->update($rowId, $quantity);

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cart_updated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove From Cart :: End ##############

    ############## Update Cart :: Start ##############
    public function cartUpdated($rowId, $quantity)
    {
        Cart::instance('cart')->update($rowId, $quantity);

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cart_updated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Update Cart :: End ##############

    ############## Remove From Cart :: Start ##############
    public function removeFromCart($rowId)
    {
        Cart::instance('cart')->remove($rowId);

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cart_updated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove From Cart :: End ##############
}
