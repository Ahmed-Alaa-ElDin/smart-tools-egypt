<?php

namespace App\Livewire\Admin\Carts;

use Livewire\Component;

class CartItemCard extends Component
{
    public $cartItem;

    public function render()
    {
        $item = $this->cartItem;
        
        return view('livewire.admin.carts.cart-item-card')->with('item', $item);
    }
}
