<?php

namespace App\Livewire\Front\General\Wishlist;

use App\Models\Product;
use Livewire\Component;
use App\Models\Collection;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class WishlistDropDown extends Component
{
    protected $listeners = ['cartUpdated' => 'render'];

    public function render()
    {
        $wishlist = Cart::instance('wishlist')->content();
        $wishlist_count = Cart::instance('wishlist')->count();

        return view('livewire.front.general.wishlist.wishlist-drop-down', compact('wishlist', 'wishlist_count'));
    }

    public function moveToCart($rowId)
    {
        // Get item from cart
        $item = Cart::instance('wishlist')->get($rowId);

        $type = $item->options->type;
        // Get the item's all data from database with best price
        if ($type == "Product") {
            $item = getBestOfferForProduct($item->id);
        } elseif ($type == "Collection") {
            $item = getBestOfferForCollection($item->id);
        }

        if ($item->quantity > 0 && $item->under_reviewing != 1) {
            Cart::instance('wishlist')->remove($rowId);

            if (!Cart::instance('cart')->search(function ($cartItem, $rowId) use ($item, $type) {
                return $cartItem->id === $item->id && $cartItem->options->type == $type;
            })->count()) {
                Cart::instance('cart')->add(
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
                        "weight" => $item->weight ?? 0,
                        "slug" => $item->slug ?? ""
                    ]
                )->associate($type == "Product" ? Product::class : Collection::class);

                if (Auth::check()) {
                    Cart::instance('cart')->store(Auth::user()->id);
                    Cart::instance('wishlist')->store(Auth::user()->id);
                }
            }

            ############ Emit event to reinitialize the slider :: Start ############
            $this->dispatch('cartUpdated');
            $this->dispatch('cartUpdated:' . "item-" . $item->id);
            ############ Emit event to reinitialize the slider :: End ############
        } elseif ($item->under_reviewing == 1) {
            ############ Emit Sweet Alert :: Start ############
            $this->dispatch('swalDone', text: __('front/homePage.Sorry This Product is Under Reviewing'),
                icon: 'error');
            ############ Emit Sweet Alert :: End ############
        } elseif ($item->quantity == 0) {
            ############ Emit Sweet Alert :: Start ############
            $this->dispatch('swalDone', text: __('front/homePage.Sorry This Product is Out of Stock'),
                icon: 'error');
            ############ Emit Sweet Alert :: End ############
        }
    }

    ############## Remove From Wishlist :: Start ##############
    public function removeFromWishlist($rowId)
    {
        Cart::instance('wishlist')->remove($rowId);

        if (Auth::check()) {
            Cart::instance('wishlist')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->dispatch('cartUpdated');
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
        $this->dispatch('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Clear Wishlist :: End ##############

}
