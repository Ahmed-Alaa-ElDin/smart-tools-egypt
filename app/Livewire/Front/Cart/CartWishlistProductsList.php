<?php

namespace App\Livewire\Front\Cart;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\Front\EnrichesCartItems;

class CartWishlistProductsList extends Component
{
    use EnrichesCartItems;

    public $items;

    ############## Get Items :: Start ##############
    #[On('cartUpdated')]
    public function getItems()
    {
        $this->items = $this->getEnrichedItems('wishlist');
    }
    ############## Get Items :: End ##############

    ############## Render :: Start ##############
    public function render()
    {
        return view('livewire.front.cart.cart-wishlist-products-list');
    }
    ############## Render :: End ##############

}
