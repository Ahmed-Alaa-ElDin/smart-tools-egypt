<?php

namespace App\Http\Livewire\Front\General\Cart;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartAmount extends Component
{
    public $product_id, $unique, $remove = true, $title = false, $small = false;

    protected function getListeners()
    {
        return [
            'cartUpdated:' . $this->unique => 'render',
            'cartCleared' => 'render',
        ];
    }

    public function render()
    {
        $cartProduct = Cart::instance('cart')
            ->search(function ($cartItem, $rowId) {
                return $cartItem->id === $this->product_id;
            })
            ->first();

        $this->cartAmount = $cartProduct ? $cartProduct->qty : 0;

        return view('livewire.front.general.cart.cart-amount', compact('cartProduct'));
    }

    ############## Add One Item To Cart :: Start ##############
    public function addOneToCart($rowId, $quantity)
    {
        $cart_product = Cart::instance('cart')->get($rowId);

        $product = Product::select('id', 'quantity')->findOrFail($cart_product->id);

        if ($product->quantity >= $quantity) {
            Cart::instance('cart')->update($rowId, $quantity);

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
            }

            ############ Emit event to reinitialize the slider :: Start ############
            $this->emit('cartUpdated');
            $this->emit('cartUpdated:' . $this->unique);
            ############ Emit event to reinitialize the slider :: End ############
        } else {
            Cart::instance('cart')->update($rowId, $product->quantity);

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
            }

            ############ Emit event to reinitialize the slider :: Start ############
            $this->emit('cartUpdated');
            $this->emit('cartUpdated:' . $this->unique);
            ############ Emit event to reinitialize the slider :: End ############

            ############ Emit Sweet Alert :: Start ############
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Sorry This Product is Out Of Stock'),
                'icon' => 'error'
            ]);
            ############ Emit Sweet Alert :: End ############
        }
    }
    ############## Add One Item To Cart :: End ##############

    ############## Remove From Cart :: Start ##############
    public function removeOneFromCart($rowId, $quantity)
    {
        Cart::instance('cart')->update($rowId, $quantity);

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        $this->emit('cartUpdated:' . $this->unique);
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove From Cart :: End ##############

    ############## Update Cart :: Start ##############
    public function cartUpdated($rowId, $quantity)
    {
        $cart_product = Cart::instance('cart')->get($rowId);

        $product = Product::select('id', 'quantity')->findOrFail($cart_product->id);

        if ($product->quantity >= $quantity) {
            Cart::instance('cart')->update($rowId, $quantity);

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
            }

            ############ Emit event to reinitialize the slider :: Start ############
            $this->emit('cartUpdated');
            $this->emit('cartUpdated:' . $this->unique);
            ############ Emit event to reinitialize the slider :: End ############
        } else {
            Cart::instance('cart')->update($rowId, $product->quantity);

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
            }

            ############ Emit event to reinitialize the slider :: Start ############
            $this->emit('cartUpdated');
            $this->emit('cartUpdated:' . $this->unique);
            ############ Emit event to reinitialize the slider :: End ############

            ############ Emit Sweet Alert :: Start ############
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Sorry This Product is Out Of Stock'),
                'icon' => 'error'
            ]);
            ############ Emit Sweet Alert :: End ############
        }
    }
    ############## Update Cart :: End ##############

    ############## Remove From Cart :: Start ##############
    public function removeFromCart($rowId, $product_id)
    {
        Cart::instance('cart')->remove($rowId);

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        $this->emit('cartUpdated:' . "product-" . $product_id);
        ############ Emit event to reinitialize the slider :: End ############

        ############ Emit Sweet Alert :: Start ############
        $this->dispatchBrowserEvent('swalDone', [
            "text" => __('front/homePage.Product Removed From Your Cart Successfully'),
            'icon' => 'success'
        ]);
        ############ Emit Sweet Alert :: End ############

    }
    ############## Remove From Cart :: End ##############

    ############## Add To Cart :: Start ##############
    public function addToCart($product_id, $quantity = 1)
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
                $product->quantity >= $quantity ? $quantity : $product->quantity,
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
