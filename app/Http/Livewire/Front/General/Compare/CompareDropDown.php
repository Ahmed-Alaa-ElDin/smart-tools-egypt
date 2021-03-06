<?php

namespace App\Http\Livewire\Front\General\Compare;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CompareDropDown extends Component
{
    protected $listeners = ['cartUpdated' => 'render'];

    public function render()
    {
        $this->compare = Cart::instance('compare')->content();
        $this->compare_count = Cart::instance('compare')->count();

        return view('livewire.front.general.compare.compare-drop-down');
    }

    public function moveToCart($rowId)
    {
        // Get product from cart
        $product = Cart::instance('compare')->get($rowId);

        // Get the product's all data from database with best price
        $product = getBestOfferForProduct($product->id);

        if ($product->quantity > 0 && $product->under_reviewing != 1) {

            Cart::instance('compare')->remove($rowId);

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
                        "slug" => $product->slug ?? ""
                    ]
                )->associate(Product::class);

                if (Auth::check()) {
                    Cart::instance('cart')->store(Auth::user()->id);
                    Cart::instance('compare')->store(Auth::user()->id);
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

    ############## Remove From Compare :: Start ##############
    public function removeFromCompare($rowId)
    {
        Cart::instance('compare')->remove($rowId);
        if (Auth::check()) {
            Cart::instance('compare')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove From Compare :: End ##############

    ############## Clear Compare :: Start ##############
    public function clearCompare()
    {
        Cart::instance('compare')->destroy();
        if (Auth::check()) {
            Cart::instance('compare')->store(Auth::user()->id);
        }

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Clear Compare :: End ##############

}
