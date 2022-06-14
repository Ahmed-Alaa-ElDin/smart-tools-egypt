<?php

namespace App\Http\Livewire\Front\General\Cart;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddToCartButton extends Component
{
    public $product_id;

    public function render()
    {
        return view('livewire.front.general.cart.add-to-cart-button');
    }

    ############## Add TO Cart :: Start ##############
    public function addToCart($product_id)
    {
        $product = getBestOffer($product_id);

        ############ Add Product to Wishlist :: Start ############
        Cart::instance('cart')->add(
            $product->id,
            [
                'en' => $product->getTranslation('name', 'en'),
                'ar' => $product->getTranslation('name', 'ar'),
            ],
            1,
            $product->best_price,
            ['thumbnail' => $product->thumbnail ?? null]
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
            "text" => __('front/homePage.Product Added to Cart Successfully'),
            'icon' => 'success'
        ]);
        ############ Emit Sweet Alert :: End ############

    }
    ############## Add TO Cart :: End ##############
}
