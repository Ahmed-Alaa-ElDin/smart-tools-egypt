<?php

namespace App\Livewire\Front\Wishlist;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use Livewire\WithPagination;

class WishlistItems extends Component
{
    use WithPagination;

    protected $listeners = ['cartUpdated' => 'render'];

    public function render()
    {
        $items = Cart::instance('wishlist')->content()->paginate(config('settings.front_pagination'));

        return view('livewire.front.wishlist.wishlist-items', compact('items'));
    }
}
