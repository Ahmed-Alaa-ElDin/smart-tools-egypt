<?php

namespace App\Http\Livewire\Front\General\Cart;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddToCartButton extends Component
{
    public $product_id, $text= false;

    public function render()
    {
        return view('livewire.front.general.cart.add-to-cart-button');
    }

    ############## Add To Cart :: Start ##############
    public function addToCart($product_id)
    {
        $product = getBestOfferForProduct($product_id);

        $cart_product = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($product) {
            return $cartItem->id === $product->id;
        })->count();

        if (!$cart_product && $product->quantity > 0 && $product->under_reviewing != 1) {
            ############ Add Product to Wishlist :: Start ############
            Cart::instance('cart')->add(
                $product->id,
                [
                    'en' => $product->getTranslation('name', 'en'),
                    'ar' => $product->getTranslation('name', 'ar'),
                ],
                1,
                $product->best_price,
                [
                    'thumbnail' => $product->thumbnail ?? null,
                    "weight" => $product->weight ?? 0,
                    "slug" => $product->slug ?? ""
                ]
            )->associate(Product::class);

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
            }
            ############ Add Product to Wishlist :: End ############

            ############ Emit event to reinitialize the slider :: Start ############
            $this->emit('cartUpdated');
            $this->emit('cartUpdated:' . "product-" . $this->product_id);
            ############ Emit event to reinitialize the slider :: End ############

            ############ Emit Sweet Alert :: Start ############
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Product Has Been Added To The Cart Successfully'),
                'icon' => 'success'
            ]);
            ############ Emit Sweet Alert :: End ############
        } elseif ($product->under_reviewing == 1) {
            ############ Emit Sweet Alert :: Start ############
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Sorry This Product is Under Reviewing'),
                'icon' => 'error'
            ]);
            ############ Emit Sweet Alert :: End ############
        } elseif ($product->quantity == 0) {
            ############ Emit Sweet Alert :: Start ############
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Sorry This Product is Out of Stock'),
                'icon' => 'error'
            ]);
            ############ Emit Sweet Alert :: End ############
        } elseif ($cart_product) {
            ############ Emit Sweet Alert :: Start ############
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Sorry This Product is Already in the Cart'),
                'icon' => 'error'
            ]);
            ############ Emit Sweet Alert :: End ############
        }

    }
    ############## Add TO Cart :: End ##############
}
