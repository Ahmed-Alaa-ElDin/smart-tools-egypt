<?php

namespace App\Http\Livewire\Front\General\Cart;

use Gloudemans\Shoppingcart\Facades\Cart as FacadesCart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartDropDown extends Component
{
    protected $listeners = ['cartUpdated' => 'render'];

    public function render()
    {
        $this->cart = FacadesCart::instance('cart')->content();

        return view('livewire.front.general.cart.cart-drop-down');
    }


    ############## Remove From Cart :: Start ##############
    public function removeFromCart($rowId, $product_id)
    {
        FacadesCart::instance('cart')->remove($rowId);

        if (Auth::check()) {
            FacadesCart::instance('cart')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        $this->emit('cartUpdated:' . "product-" . $product_id);
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove From Cart :: End ##############

    ############## Move To Wishlist :: Start ##############
    public function moveToWishlist($rowId)
    {
        // Get product from cart
        $product = FacadesCart::instance('cart')->get($rowId);

        // Get the product's all data from database with best price
        $product = getBestOfferForProduct($product->id);

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
                [
                    'thumbnail' => $product->thumbnail ?? null,
                    "slug" => $product->slug ?? ""
                ]
            )->associate(Product::class);

            if (Auth::check()) {
                FacadesCart::instance('cart')->store(Auth::user()->id);
                FacadesCart::instance('wishlist')->store(Auth::user()->id);
            }
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        $this->emit('cartUpdated:' . "product-" . $product->id);
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
        $this->emit('cartUpdated');
        $this->emit('cartCleared');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Clear Cart :: End ##############
}
