<?php

namespace App\Http\Livewire\Front\Homepage;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductsList extends Component
{
    public $section;
    public $key;
    public $amount;

    ############## Mount :: Start ##############
    public function mount()
    {
        $this->products = $this->section->finalProducts->toArray();
    }
    ############## Mount :: End ##############

    ############## Render Section :: Start ##############
    public function render()
    {
        return view('livewire.front.homepage.products-list');
    }
    ############## Render Section :: End ##############

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

        // todo
        // Cart::destroy();

        ############ Add Product to Wishlist :: End ############

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cart_updated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Add TO Cart :: End ##############

    ############## Add To Wishlist :: Start ##############
    public function addToWishlist($product_id)
    {
        $product = getBestOffer($product_id);

        ############ Add Product to Wishlist :: Start ############
        $in_wishlist = Cart::instance('wishlist')->search(function ($cartItem, $rowId) use ($product) {
            return $cartItem->id === $product->id;
        })->count();

        if (!$in_wishlist) {
            Cart::instance('wishlist')->add($product->id, [
                'en' => $product->getTranslation('name', 'en'),
                'ar' => $product->getTranslation('name', 'ar'),
            ], 1, $product->best_price, ['thumbnail' => $product->thumbnail ?? null])->associate(Product::class);

            if (Auth::check()) {
                Cart::instance('wishlist')->store(Auth::user()->id);
            }
        }
        ############ Add Product to Wishlist :: End ############

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('product_added_to_wishlist');
        ############ Emit event to reinitialize the slider :: End ############
    }
}
