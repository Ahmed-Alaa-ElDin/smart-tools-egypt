<?php

namespace App\Livewire\Front\Order\OrderForm;

use App\Models\Product;

use Livewire\Component;
use App\Models\Collection;

use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use App\Traits\Front\EnrichesCartItems;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartSection extends Component
{
    use EnrichesCartItems;

    #[On('cartUpdated')]
    public function handleCartUpdated()
    {
        // Triggers re-render
    }

    public function removeItem($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        $this->updateCart();
    }

    public function removeFromCart($id, $type)
    {
        $item = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($id, $type) {
            return $cartItem->id == $id && $cartItem->options->type == $type;
        })->first();

        if ($item) {
            Cart::instance('cart')->remove($item->rowId);
            $this->updateCart();
            $this->dispatch(
                'swalDone',
                text: __('front/homePage.Product Removed From Your Cart Successfully'),
                icon: 'success'
            );
        }
    }

    protected function updateCart()
    {
        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }

        $this->dispatch('cartUpdated');
    }

    #[Computed]
    public function cart()
    {
        return $this->getEnrichedItems('cart');
    }

    #[Computed]
    public function cart_count()
    {
        return Cart::instance('cart')->count();
    }

    public function render()
    {
        return view('livewire.front.order.order-form.cart-section');
    }
}
