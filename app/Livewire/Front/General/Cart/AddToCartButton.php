<?php

namespace App\Livewire\Front\General\Cart;

use App\Models\Product;
use Livewire\Component;
use App\Models\Collection;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class AddToCartButton extends Component
{
    public $item_id, $type = 'Product', $text = false, $large = false, $add_buy = 'add';

    public function render()
    {
        return view('livewire.front.general.cart.add-to-cart-button');
    }

    ############## Add To Cart :: Start ##############
    public function addToCart($item_id, $type)
    {
        if ($type == 'Product') {
            $item = getBestOfferForProduct($item_id);
        } elseif ($type == 'Collection') {
            $item = getBestOfferForCollection($item_id);
        }

        $cart_item = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($item, $type) {
            return $cartItem->id === $item->id && $cartItem->options->type === $type;
        })->count();

        if (!$cart_item && $item->quantity > 0 && $item->under_reviewing != 1) {
            ############ Add Item to Wishlist :: Start ############
            Cart::instance('cart')->add(
                $item->id,
                [
                    'en' => $item->getTranslation('name', 'en'),
                    'ar' => $item->getTranslation('name', 'ar'),
                ],
                1,
                $item->best_price,
                [
                    'type'  => $type,
                    'thumbnail' => $item->thumbnail ?? null,
                    "weight" => $item->weight ?? 0,
                    "slug" => $item->slug ?? ""
                ]
            )->associate($item['type'] == 'Product' ? Product::class : Collection::class);

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
            }
            ############ Add Item to Wishlist :: End ############

            ############ Emit event to reinitialize the slider :: Start ############
            $this->dispatch('cartUpdated');
            $this->dispatch('cartUpdated:' . "item-" . $this->item_id);
            ############ Emit event to reinitialize the slider :: End ############

            ############ Emit Sweet Alert :: Start ############
            $this->dispatch(
                'swalDone',
                text: __('front/homePage.Product Has Been Added To The Cart Successfully'),
                icon: 'success'
            );
            ############ Emit Sweet Alert :: End ############

            if ($this->add_buy == 'pay') {
                ############ Go to Payment :: Start ############
                // redirect()->route('front.order.shipping');
                ############ Go to Payment :: End ############
            }
        } elseif ($item->under_reviewing == 1) {
            ############ Emit Sweet Alert :: Start ############
            $this->dispatch('swalDone', text: __('front/homePage.Sorry This Product is Under Reviewing'),
                icon: 'error');
            ############ Emit Sweet Alert :: End ############
        } elseif ($item->quantity == 0) {
            ############ Emit Sweet Alert :: Start ############
            $this->dispatch('swalDone', text: __('front/homePage.Sorry This Product is Out of Stock'),
                icon: 'error');
            ############ Emit Sweet Alert :: End ############
        } elseif ($cart_item) {
            if ($this->add_buy == 'pay') {
                ############ Go to Payment :: Start ############
                redirect()->route('front.order.shipping');
                ############ Go to Payment :: End ############
            } else {
                ############ Emit Sweet Alert :: Start ############
                $this->dispatch('swalDone', text:__('front/homePage.Sorry This Product is Already in the Cart'),
                    icon: 'error');
                ############ Emit Sweet Alert :: End ############
            }
        }
    }
    ############## Add TO Cart :: End ##############
}
