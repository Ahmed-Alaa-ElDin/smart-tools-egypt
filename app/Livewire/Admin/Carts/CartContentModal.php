<?php

namespace App\Livewire\Admin\Carts;

use App\Models\Cart;
use Livewire\Component;

class CartContentModal extends Component
{
    public $showCartContent = false;
    public $identifier;
    public $cart;
    protected $listeners = ['showCartItems'];

    public function render()
    {
        return view('livewire.admin.carts.cart-content-modal');
    }

    public function showCartItems($identifier)
    {
        $this->identifier = $identifier;

        $this->cart = Cart::where('identifier', $identifier)
            ->where('instance', 'cart')
            ->first();
    }

    // public function completeOrder()
    // {
    //     $this->cart->update([
    //         'instance' => 'order',
    //     ]);
    // }

    public function cancelOrder()
    {
        $this->dispatch('deleteCart', identifier: $this->identifier);

        $this->identifier = null;
        $this->cart = null;

        $this->closeModal();
    }

    public function closeModal()
    {
        $this->identifier = null;
        $this->cart = null;

        $this->showCartContent = false;

        $this->dispatch('hideCartContentModal');
    }
}
