<?php

namespace App\Livewire\Front\General\Cart;

use App\Models\Product;
use Livewire\Component;
use App\Models\Collection;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartDropDown extends Component
{
    protected $listeners = ['cartUpdated' => 'render'];

    public function render()
    {
        $cart = Cart::instance('cart')->content();

        return view('livewire.front.general.cart.cart-drop-down', compact('cart'));
    }


    ############## Remove From Cart :: Start ##############
    public function removeFromCart($rowId, $item_id)
    {
        Cart::instance('cart')->remove($rowId);

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->dispatch('cartUpdated');
        $this->dispatch('cartUpdated:' . "item-" . $item_id);
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove From Cart :: End ##############

    ############## Move To Wishlist :: Start ##############
    public function moveToWishlist($rowId, $type)
    {
        // Get item from cart
        $item = Cart::instance('cart')->get($rowId);

        // Get the item's all data from database with best price
        if ($item->options->type == 'Product') {
            $item = getBestOfferForProduct($item->id);
        } elseif ($item->options->type == 'Collection') {
            $item = getBestOfferForCollection($item->id);
        }

        // Remove item from cart
        Cart::instance('cart')->remove($rowId);

        // Add item to wishlist
        if (!Cart::instance('wishlist')->search(function ($cartItem, $rowId) use ($item, $type) {
            return $cartItem->id === $item->id && $cartItem->options['type'] === $type;
        })->count()) {
            Cart::instance('wishlist')->add(
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
                    "slug" => $item->slug ?? ""
                ]
            )->associate($item['type'] == 'Product' ? Product::class : Collection::class);

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
                Cart::instance('wishlist')->store(Auth::user()->id);
            }
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->dispatch('cartUpdated');
        $this->dispatch('cartUpdated:' . "item-" . $item->id);
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Move To Wishlist :: End ##############

    ############## Clear Cart :: Start ##############
    public function clearCart()
    {
        Cart::instance('cart')->destroy();

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->dispatch('cartUpdated');
        $this->dispatch('cartCleared');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Clear Cart :: End ##############
}
