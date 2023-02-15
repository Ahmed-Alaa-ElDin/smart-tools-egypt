<?php

namespace App\Http\Livewire\Front\General\Cart;

use App\Models\Collection;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartAmount extends Component
{
    public $item_id, $type, $unique, $remove = true, $title = false, $small = false;

    protected function getListeners()
    {
        return [
            'cartUpdated:' . $this->unique => 'render',
            'cartCleared' => 'render',
        ];
    }

    public function render()
    {
        $cartItem = Cart::instance('cart')
            ->search(function ($cartItem, $rowId) {
                return $cartItem->id === $this->item_id && $cartItem->options->type == $this->type;
            })
            ->first();

        $this->cartAmount = $cartItem ? $cartItem->qty : 0;

        return view('livewire.front.general.cart.cart-amount', compact('cartItem'));
    }

    ############## Add One Item To Cart :: Start ##############
    public function addOneToCart($rowId, $quantity)
    {
        $cart_item = Cart::instance('cart')->get($rowId);

        if ($this->type == "Product") {
            $item = Product::select('id', 'quantity')->findOrFail($cart_item->id);
        } elseif ($this->type == "Collection") {
            $item = Collection::select('id')->findOrFail($cart_item->id);
        }


        if ($item->quantity >= $quantity) {
            Cart::instance('cart')->update($rowId, $quantity);

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
            }

            ############ Emit event to reinitialize the slider :: Start ############
            $this->emit('cartUpdated');
            $this->emit('cartUpdated:' . $this->unique);
            ############ Emit event to reinitialize the slider :: End ############
        } else {
            Cart::instance('cart')->update($rowId, $item->quantity);

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
        $cart_item = Cart::instance('cart')->get($rowId);

        if ($this->type == "Product") {
            $item = Product::select('id', 'quantity')->findOrFail($cart_item->id);
        } elseif ($this->type == "Collection") {
            $item = Collection::select('id')->findOrFail($cart_item->id);
        }

        if ($item->quantity >= $quantity) {
            Cart::instance('cart')->update($rowId, $quantity);

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
            }

            ############ Emit event to reinitialize the slider :: Start ############
            $this->emit('cartUpdated');
            $this->emit('cartUpdated:' . $this->unique);
            ############ Emit event to reinitialize the slider :: End ############
        } else {
            Cart::instance('cart')->update($rowId, $item->quantity);

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
    public function removeFromCart($rowId, $item_id)
    {
        Cart::instance('cart')->remove($rowId);

        if (Auth::check()) {
            Cart::instance('cart')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        $this->emit('cartUpdated:' . $this->unique);
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
    public function addToCart($item_id, $quantity = 1)
    {

        if ($this->type == 'Product') {
            $item = getBestOfferForProduct($item_id);
        } elseif ($this->type == 'Collection') {
            $item = getBestOfferForCollection($item_id);
        }

        $cart_item = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($item) {
            return $cartItem->id === $item->id && $cartItem->options->type == $this->type;
        })->count();

        if (!$cart_item && $item->quantity > 0 && $item->under_reviewing != 1) {
            ############ Add Product to Wishlist :: Start ############
            Cart::instance('cart')->add(
                $item->id,
                [
                    'en' => $item->getTranslation('name', 'en'),
                    'ar' => $item->getTranslation('name', 'ar'),
                ],
                $item->quantity >= $quantity ? $quantity : $item->quantity,
                $item->best_price,
                [
                    'type' => $this->type,
                    'thumbnail' => $item->thumbnail ?? null,
                    "weight" => $item->weight ?? 0,
                    "slug" => $item->slug ?? ""
                ]
            )->associate($this->type == "Product" ? Product::class : Collection::class);

            if (Auth::check()) {
                Cart::instance('cart')->store(Auth::user()->id);
            }
            ############ Add Product to Wishlist :: End ############

            ############ Emit event to reinitialize the slider :: Start ############
            $this->emit('cartUpdated');
            $this->emit('cartUpdated:' . $this->unique);
            ############ Emit event to reinitialize the slider :: End ############

            ############ Emit Sweet Alert :: Start ############
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Product Has Been Added To The Cart Successfully'),
                'icon' => 'success'
            ]);
            ############ Emit Sweet Alert :: End ############
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
        } elseif ($cart_item) {
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
