<?php

namespace App\Http\Livewire\Front\General\Wishlist;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Wishlist extends Component
{
    protected $listeners = ['cartUpdated' => 'render'];

    public function render()
    {
        $this->wishlist = Cart::instance('wishlist')->content();
        $this->wishlist_count = Cart::instance('wishlist')->count();

        return view('livewire.front.general.wishlist.wishlist');
    }

    public function moveToCart($rowId)
    {
        // Get product from cart
        $product = Cart::instance('wishlist')->get($rowId);

        // Get the product's all data from database with best price
        $product = getBestOffer($product->id);

        Cart::instance('wishlist')->remove($rowId);

        if (!Cart::instance('cart')->search(function ($cartItem, $rowId) use ($product) {
            return $cartItem->id === $product->id;
        })->count()) {
            Cart::instance('cart')->add(
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
                Cart::instance('cart')->store(Auth::user()->id);
                Cart::instance('wishlist')->store(Auth::user()->id);
            }
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        $this->emit('cartUpdated:' . "product-" . $product->id);
        ############ Emit event to reinitialize the slider :: End ############
    }

    ############## Remove From Wishlist :: Start ##############
    public function removeFromWishlist($rowId)
    {
        Cart::instance('wishlist')->remove($rowId);

        if (Auth::check()) {
            Cart::instance('wishlist')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove From Wishlist :: End ##############

    ############## Clear Wishlist :: Start ##############
    public function clearWishlist()
    {
        Cart::instance('wishlist')->destroy();

        if (Auth::check()) {
            Cart::instance('wishlist')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Clear Wishlist :: End ##############

}
