<?php

namespace App\Livewire\Front\General\Wishlist;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddToWishlistButton extends Component
{
    public $item_id, $type, $text = false, $large = false, $remove = false;

    public function render()
    {
        return view('livewire.front.general.wishlist.add-to-wishlist-button');
    }

    ############## Add To Wishlist :: Start ##############
    public function addToWishlist($item_id, $type)
    {
        ############ Remove Product from Cart :: Start ############
        if ($this->remove) {
            Cart::instance('cart')->search(function ($cartItem, $rowId) use ($item_id, $type) {
                return $cartItem->id === $item_id && $cartItem->options->type === $type;
            })->each(function ($cartItem, $rowId) {
                Cart::instance('cart')->remove($rowId);
            });

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
            }
        }
        ############ Remove Product from Cart :: End ############

        if ($type == 'Product') {
            $item = getBestOfferForProduct($item_id);
        } elseif ($type == 'Collection') {
            $item = getBestOfferForCollection($item_id);
        }


        ############ Add Product to Wishlist :: Start ############
        $in_wishlist = Cart::instance('wishlist')->search(function ($cartItem, $rowId) use ($item, $type) {
            return $cartItem->id === $item->id && $cartItem->options->type == $type;
        })->count();

        if (!$in_wishlist) {
            Cart::instance('wishlist')->add(
                $item->id,
                [
                    'en' => $item->getTranslation('name', 'en'),
                    'ar' => $item->getTranslation('name', 'ar'),
                ],
                1,
                $item->best_price,
                [
                    'type' => $type,
                    'thumbnail' => $item->thumbnail ?? null,
                    "slug" => $item->slug ?? ""
                ]
            )->associate($type == 'Product' ?  Product::class : Collection::class);

            if (Auth::check()) {
                Cart::instance('wishlist')->store(Auth::user()->id);
            }

            ############ Emit Sweet Alert :: Start ############
            $this->dispatch('swalDone', text: __('front/homePage.Product Has Been Added To The Wishlist Successfully'),
                icon: 'success');
            ############ Emit Sweet Alert :: End ############
        }
        ############ Add Product to Wishlist :: End ############
        else {
            ############ Emit Sweet Alert :: Start ############
            $this->dispatch('swalDone', text: __('front/homePage.Sorry This Product is Already in the Wishlist'),
                icon: 'error');
            ############ Emit Sweet Alert :: End ############
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->dispatch('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
}
