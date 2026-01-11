<?php

namespace App\Livewire\Front\Cart;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Traits\Front\EnrichesCartItems;

class CartProductsList extends Component
{
    use EnrichesCartItems;

    public $items;

    ############## Get Items :: Start ##############
    #[On('cartUpdated')]
    public function getItems()
    {
        $this->items = $this->getEnrichedItems('cart');
    }
    ############## Get Items :: End ##############

    public function render()
    {
        return view('livewire.front.cart.cart-products-list');
    }
}
