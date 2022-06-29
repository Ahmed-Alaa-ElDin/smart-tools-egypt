<?php

namespace App\Http\Livewire\Front\Order\General;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderWishlistProductsList extends Component
{
    public $products;

    protected $listeners = [
        'cartUpdated' => 'getProducts',
    ];

    public function render()
    {
        return view('livewire.front.order.general.order-wishlist-products-list');
    }

    ############## Get Products :: Start ##############
    public function getProducts()
    {
        $products_id = Cart::instance('wishlist')->content()->pluck('id');

        $products = [];

        foreach ($products_id as $product_id) {
            $products[] = getBestOffer($product_id)->toArray();
        }

        $this->products = $products;
    }
    ############## Get Products :: End ##############

    ############## Remove Products from Wishlist :: Start ##############
    public function removeFromWishlist($product_id)
    {
        Cart::instance('wishlist')->search(function ($cartItem, $rowId) use ($product_id) {
            return $cartItem->id === $product_id;
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
    ############## Remove Products from Wishlist :: End ##############

    ############## Add Products to Cart :: Start ##############
    public function moveToCart($product_id)
    {
        // Get product from cart
        $product = Cart::instance('wishlist')->search(function ($cartItem, $rowId) use ($product_id) {
            return $cartItem->id === $product_id;
        })->first();

        $product_id = $product->rowId;

        // Get the product's all data from database with best price
        $product = getBestOffer($product->id);

        if ($product->quantity > 0 && $product->under_reviewing != 1) {
            Cart::instance('wishlist')->remove($product_id);

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

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Product Has Been Added To The Cart Successfully'),
                'icon' => 'success'
            ]);
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
}
