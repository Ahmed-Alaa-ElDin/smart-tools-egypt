<?php

namespace App\Http\Livewire\Front\General\Cart;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartAmount extends Component
{
    public $product_id, $unique, $remove = true;

    protected function getListeners()
    {
        return [
            'cartUpdated:' . $this->unique => 'render',
            'cartCleared' => 'render',
        ];
    }

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
        $cart_product = Cart::instance('cart')->get($rowId);

        $product = Product::select('id', 'quantity')->findOrFail($cart_product->id);

        if ($product->quantity >= $quantity) {
            Cart::instance('cart')->update($rowId, $quantity);

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
            }

            ############ Emit event to reinitialize the slider :: Start ############
            $this->emit('cartUpdated');
            $this->emit('cartUpdated:' . $this->unique);
            ############ Emit event to reinitialize the slider :: End ############
        } else {
            Cart::instance('cart')->update($rowId, $product->quantity);

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
            }

            ############ Emit event to reinitialize the slider :: Start ############
            $this->emit('cartUpdated');
            $this->emit('cartUpdated:' . $this->unique);
            ############ Emit event to reinitialize the slider :: End ############

            ############ Emit Sweet Alert :: Start ############
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Sorry This Product is Out Of Stock'),
                'icon' => 'error'
            ]);
            ############ Emit Sweet Alert :: End ############
        }
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
        $this->emit('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove From Cart :: End ##############

    ############## Update Cart :: Start ##############
    public function cartUpdated($rowId, $quantity)
    {
        $cart_product = Cart::instance('cart')->get($rowId);

        $product = Product::select('id', 'quantity')->findOrFail($cart_product->id);

        if ($product->quantity >= $quantity) {
            Cart::instance('cart')->update($rowId, $quantity);

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
            }

            ############ Emit event to reinitialize the slider :: Start ############
            $this->emit('cartUpdated');
            $this->emit('cartUpdated:' . $this->unique);
            ############ Emit event to reinitialize the slider :: End ############
        } else {
            Cart::instance('cart')->update($rowId, $product->quantity);

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
            }

            ############ Emit event to reinitialize the slider :: Start ############
            $this->emit('cartUpdated');
            $this->emit('cartUpdated:' . $this->unique);
            ############ Emit event to reinitialize the slider :: End ############

            ############ Emit Sweet Alert :: Start ############
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Sorry This Product is Out Of Stock'),
                'icon' => 'error'
            ]);
            ############ Emit Sweet Alert :: End ############
        }
    }
    ############## Update Cart :: End ##############

    ############## Remove From Cart :: Start ##############
    public function removeFromCart($rowId, $product_id)
    {
        Cart::instance('cart')->remove($rowId);

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        $this->emit('cartUpdated:' . "product-" . $product_id);
        ############ Emit event to reinitialize the slider :: End ############

        ############ Emit Sweet Alert :: Start ############
        $this->dispatchBrowserEvent('swalDone', [
            "text" => __('front/homePage.Product Removed From Your Cart Successfully'),
            'icon' => 'success'
        ]);
        ############ Emit Sweet Alert :: End ############

    }
    ############## Remove From Cart :: End ##############
}
