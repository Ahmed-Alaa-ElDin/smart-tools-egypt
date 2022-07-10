<?php

namespace App\Http\Livewire\Front\General\Wishlist;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Wishlist extends Component
{
    protected $listeners = ['cartUpdated' => 'render'];

    public function render()
    {
        $this->wishlist = Cart::instance('wishlist')->content();
        $this->wishlist_count = Cart::instance('wishlist')->count();

        return view('livewire.front.general.wishlist.wishlist');
    }

    public function moveToCart($rowId)
    {
        // Get product from cart
        $product = Cart::instance('wishlist')->get($rowId);

        // Get the product's all data from database with best price
        $product = getBestOfferForProduct($product->id);

        if ($product->quantity > 0 && $product->under_reviewing != 1) {
            Cart::instance('wishlist')->remove($rowId);

            if (!Cart::instance('cart')->search(function ($cartItem, $rowId) use ($product) {
                return $cartItem->id === $product->id;
            })->count()) {
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
                    ]
                )->associate(Product::class);

                if (Auth::check()) {
                    Cart::instance('cart')->store(Auth::user()->id);
                    Cart::instance('wishlist')->store(Auth::user()->id);
                }
            }

            ############ Emit event to reinitialize the slider :: Start ############
            $this->emit('cartUpdated');
            $this->emit('cartUpdated:' . "product-" . $product->id);
            ############ Emit event to reinitialize the slider :: End ############
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
        }
    }

    ############## Remove From Wishlist :: Start ##############
    public function removeFromWishlist($rowId)
    {
        Cart::instance('wishlist')->remove($rowId);

        if (Auth::check()) {
            Cart::instance('wishlist')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove From Wishlist :: End ##############

    ############## Clear Wishlist :: Start ##############
    public function clearWishlist()
    {
        Cart::instance('wishlist')->destroy();

        if (Auth::check()) {
            Cart::instance('wishlist')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Clear Wishlist :: End ##############

}
