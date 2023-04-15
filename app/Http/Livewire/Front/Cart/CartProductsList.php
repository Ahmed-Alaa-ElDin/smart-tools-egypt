<?php

namespace App\Http\Livewire\Front\Cart;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartProductsList extends Component
{
    public $items;

    protected $listeners = [
        'cartUpdated' => 'getItems',
    ];

    public function render()
    {
        return view('livewire.front.cart.cart-products-list');
    }

    ############## Get Items :: Start ##############
    public function getItems()
    {
        $products_id = [];
        $collections_id = [];

        // get items id from cart
        Cart::instance('cart')->content()->map(function ($item) use (&$products_id, &$collections_id, &$cart_products_id, &$cart_collections_id) {
            if ($item->options->type == 'Product') {
                $products_id[] = $item->id;
            } elseif ($item->options->type == 'Collection') {
                $collections_id[] = $item->id;
            }
        });

        // get all items data from database with best price
        $products = getBestOfferForProducts($products_id);
        $collections = getBestOfferForCollections($collections_id);

        $this->items = $collections->concat($products)->toArray();
    }
    ############## Get Items :: End ##############

    ############## Remove Items from Cart :: Start ##############
    public function removeFromCart($item_id , $type)
    {
        Cart::instance('cart')->search(function ($cartItem, $rowId) use ($item_id, &$type) {
            return $cartItem->id === $item_id && $cartItem->options->type === $type;
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
