<?php

namespace App\Http\Livewire\Front\General\Wishlist;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddToWishlistButton extends Component
{
    public $product_id, $text = false, $remove = false;

    public function render()
    {
        return view('livewire.front.general.wishlist.add-to-wishlist-button');
    }

    ############## Add To Wishlist :: Start ##############
    public function addToWishlist($product_id)
    {
        ############ Remove Product from Cart :: Start ############
        if ($this->remove) {
            Cart::instance('cart')->search(function ($cartItem, $rowId) use ($product_id) {
                return $cartItem->id === $product_id;
            })->each(function ($cartItem, $rowId) {
                Cart::instance('cart')->remove($rowId);
            });

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
            }
        }
        ############ Remove Product from Cart :: End ############

        $product = getBestOffer($product_id);

        ############ Add Product to Wishlist :: Start ############
        $in_wishlist = Cart::instance('wishlist')->search(function ($cartItem, $rowId) use ($product) {
            return $cartItem->id === $product->id;
        })->count();

        if (!$in_wishlist) {
            Cart::instance('wishlist')->add($product->id, [
                'en' => $product->getTranslation('name', 'en'),
                'ar' => $product->getTranslation('name', 'ar'),
            ], 1, $product->best_price, ['thumbnail' => $product->thumbnail ?? null])->associate(Product::class);

            if (Auth::check()) {
                Cart::instance('wishlist')->store(Auth::user()->id);
            }

            ############ Emit Sweet Alert :: Start ############
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Product Has Been Added To The Wishlist Successfully'),
                'icon' => 'success'
            ]);
            ############ Emit Sweet Alert :: End ############
        }
        ############ Add Product to Wishlist :: End ############

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
}
