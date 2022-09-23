<?php

namespace App\Http\Livewire\Front\General\Compare;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddToCompareButton extends Component
{
    public $product_id;

    public $large = false, $text = false;

    public function render()
    {
        return view('livewire.front.general.compare.add-to-compare-button');
    }

    ############## Add To Compare :: Start ##############
    public function addToCompare($product_id)
    {
        $product = getBestOfferForProduct($product_id);

        ############ Add Product to Compare :: Start ############
        $in_compare = Cart::instance('compare')->search(function ($cartItem, $rowId) use ($product) {
            return $cartItem->id === $product->id;
        })->count();

        if (!$in_compare && Cart::instance('compare')->count() < 3) {
            Cart::instance('compare')->add(
                $product->id,
                [
                    'en' => $product->getTranslation('name', 'en'),
                    'ar' => $product->getTranslation('name', 'ar'),
                ],
                1,
                $product->best_price,
                [
                    'thumbnail' => $product->thumbnail ?? null,
                    "slug" => $product->slug ?? ""
                ]
            )->associate(Product::class);

            if (Auth::check()) {
                Cart::instance('compare')->store(Auth::user()->id);
            }

            ############ Emit Sweet Alert :: Start ############
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Product Has Been Added To The Comparison Successfully'),
                'icon' => 'success'
            ]);
            ############ Emit Sweet Alert :: End ############
        } elseif (Cart::instance('compare')->count() >= 3) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.Comparison Limit Reached'),
                'icon' => 'error'
            ]);
        }
        ############ Add Product to Compare :: End ############

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Add To Compare :: End ##############
}
