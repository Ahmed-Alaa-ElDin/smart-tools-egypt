<?php

namespace App\Http\Livewire\Front\General\Cart;

use Gloudemans\Shoppingcart\Facades\Cart as FacadesCart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Cart extends Component
{
    protected $listeners = ['cart_updated' => 'render'];

    public function render()
    {
        $this->cart = FacadesCart::instance('cart')->content();

        return view('livewire.front.general.cart.cart');
    }


    ############## Remove From Cart :: Start ##############
    public function removeFromCart($rowId)
    {
        FacadesCart::instance('cart')->remove($rowId);
        if (Auth::check()) {
            FacadesCart::instance('cart')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cart_updated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove From Cart :: End ##############

    ############## Move To Wishlist :: Start ##############
    public function moveToWishlist($rowId)
    {
        // Get product from cart
        $product = FacadesCart::instance('cart')->get($rowId);

        // Get the product's all data from database with best price
        $product = getBestOffer($product->id);

        // Remove product from cart
        FacadesCart::instance('cart')->remove($rowId);

        // Add product to wishlist
        if (!FacadesCart::instance('wishlist')->search(function ($cartItem, $rowId) use ($product) {
            return $cartItem->id === $product->id;
        })->count()) {
            FacadesCart::instance('wishlist')->add(
                $product->id,
                [
                    'en' => $product->getTranslation('name', 'en'),
                    'ar' => $product->getTranslation('name', 'ar'),
                ],
                1,
                $product->best_price,
                ['thumbnail' => $product->thumbnail ?? null]
            )->associate(Product::class);

            if (Auth::check()) {
                FacadesCart::instance('cart')->store(Auth::user()->id);
                FacadesCart::instance('wishlist')->store(Auth::user()->id);
            }
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cart_updated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Move To Wishlist :: End ##############

    ############## Clear Cart :: Start ##############
    public function clearCart()
    {
        FacadesCart::instance('cart')->destroy();

        if (Auth::check()) {
            FacadesCart::instance('cart')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cart_updated');
        ############ Emit event to reinitialize the slider :: End ############
    }
}
