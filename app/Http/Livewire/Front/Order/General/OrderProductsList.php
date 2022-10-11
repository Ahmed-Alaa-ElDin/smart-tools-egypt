<?php

namespace App\Http\Livewire\Front\Order\General;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderProductsList extends Component
{
    public $items;
    public $step;

    protected $listeners = [
        'cartUpdated' => 'getItems',
    ];

    public function render()
    {
        return view('livewire.front.order.general.order-products-list');
    }

    ############## Get Items :: Start ##############
    public function getItems()
    {
        $items_id = Cart::instance('cart')->content()->pluck('id');

        $items = [];

        $items = getBestOfferForProducts($items_id);

        $this->items = $items;
    }
    ############## Get Items :: End ##############

    ############## Remove Items from Cart :: Start ##############
    public function removeFromCart($item_id)
    {
        Cart::instance('cart')->search(function ($cartItem, $rowId) use ($item_id) {
            return $cartItem->id === $item_id;
        })->each(function ($cartItem, $rowId) {
            Cart::instance('cart')->remove($rowId);
        });

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }

        ############ Emit Sweet Alert :: Start ############
        $this->dispatchBrowserEvent('swalDone', [
            "text" => __('front/homePage.Product Removed From Your Cart Successfully'),
            'icon' => 'success'
        ]);
        ############ Emit Sweet Alert :: End ############

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove Items from Cart :: End ##############


}
