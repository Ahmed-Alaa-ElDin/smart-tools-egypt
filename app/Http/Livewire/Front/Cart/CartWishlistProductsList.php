<?php

namespace App\Http\Livewire\Front\Cart;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartWishlistProductsList extends Component
{
    public $items;

    protected $listeners = [
        'cartUpdated' => 'getItems',
    ];

    public function render()
    {
        return view('livewire.front.cart.cart-wishlist-products-list');
    }

    ############## Get Items :: Start ##############
    public function getItems()
    {
        $products_id = [];
        $collections_id = [];

        // get items id from wishlist
        Cart::instance('wishlist')->content()->map(function ($item) use (&$products_id, &$collections_id, &$cart_products_id, &$cart_collections_id) {
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

    ############## Remove Items from Wishlist :: Start ##############
    public function removeFromWishlist($item_id, $type)
    {
        Cart::instance('wishlist')->search(function ($cartItem, $rowId) use ($item_id, $type) {
            return $cartItem->id === $item_id && $cartItem->options->type === $type;
        })->each(function ($cartItem, $rowId) {
            Cart::instance('wishlist')->remove($rowId);
        });

        if (Auth::check()) {
            Cart::instance('wishlist')->store(Auth::user()->id);
        }

        ############ Emit Sweet Alert :: Start ############
        $this->dispatchBrowserEvent('swalDone', [
            "text" => __('front/homePage.Product Has Been Removed From Your Wishlist Successfully'),
            'icon' => 'success'
        ]);
        ############ Emit Sweet Alert :: End ############

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove Items from Wishlist :: End ##############

    ############## Add Items to Cart :: Start ##############
    public function moveToCart($item_id, $type)
    {
        // Get item from cart
        $item = Cart::instance('wishlist')->search(function ($cartItem, $rowId) use ($item_id, $type) {
            return $cartItem->id === $item_id && $cartItem->options->type === $type;
        })->first();

        $item_id = $item->rowId;

        // Get the item's all data from database with best price
        if ($type === 'Product') {
            $item = getBestOfferForProduct($item->id);
        } else {
            $item = getBestOfferForCollection($item->id);
        }

        if ($item->quantity > 0 && $item->under_reviewing != 1) {
            Cart::instance('wishlist')->remove($item_id);

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
                )->associate(Product::class);

                if (Auth::check()) {
                    Cart::instance('cart')->store(Auth::user()->id);
                    Cart::instance('wishlist')->store(Auth::user()->id);
                }
            }

            ############ Emit event to reinitialize the slider :: Start ############
            $this->emit('cartUpdated');
            $this->emit('cartUpdated:' . "item-" . $item->id);
            ############ Emit event to reinitialize the slider :: End ############

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Product Has Been Added To The Cart Successfully'),
                'icon' => 'success'
            ]);
        } elseif ($item->under_reviewing == 1) {
            ############ Emit Sweet Alert :: Start ############
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Sorry This Product is Under Reviewing'),
                'icon' => 'error'
            ]);
            ############ Emit Sweet Alert :: End ############
        } elseif ($item->quantity == 0) {
            ############ Emit Sweet Alert :: Start ############
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Sorry This Product is Out of Stock'),
                'icon' => 'error'
            ]);
            ############ Emit Sweet Alert :: End ############
        }
    }
}
