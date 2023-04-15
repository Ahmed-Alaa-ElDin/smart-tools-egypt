<?php

namespace App\Http\Livewire\Front\General\Wishlist;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class RemoveFromWishlistButton extends Component
{
    public $item_id, $type, $text = false, $large = false, $remove = false;

    public function render()
    {
        return view('livewire.front.general.wishlist.remove-from-wishlist-button');
    }

    ############## Add To Wishlist :: Start ##############
    public function removeFromWishlist($item_id, $type)
    {
        Cart::instance('wishlist')->search(function ($cartItem, $rowId) use ($item_id, $type) {
            return $cartItem->id === $item_id && $cartItem->options->type === $type;
        })->each(function ($cartItem, $rowId) {
            Cart::instance('wishlist')->remove($rowId);
        });

        if (auth()->check()) {
            Cart::instance('wishlist')->store(auth()->user()->id);
        }

        ############ Emit Sweet Alert :: Start ############
        $this->dispatchBrowserEvent('swalDone', [
            "text" => __('front/homePage.Product Has Been Removed From Wishlist Successfully'),
            'icon' => 'success'
        ]);
        ############ Emit Sweet Alert :: End ############

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
}
