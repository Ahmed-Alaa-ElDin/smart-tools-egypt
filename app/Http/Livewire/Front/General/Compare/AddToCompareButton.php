<?php

namespace App\Http\Livewire\Front\General\Compare;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddToCompareButton extends Component
{
    public $item_id;

    public $type, $large = false, $text = false;

    public function render()
    {
        return view('livewire.front.general.compare.add-to-compare-button');
    }

    ############## Add To Compare :: Start ##############
    public function addToCompare($item_id, $type)
    {
        if ($type == 'Product') {
            $item = getBestOfferForProduct($item_id);
        } elseif ($type == 'Collection') {
            $item = getBestOfferForCollection($item_id);
        }

        ############ Add Product to Compare :: Start ############
        $in_compare = Cart::instance('compare')->search(function ($cartItem, $rowId) use ($item, $type) {
            return $cartItem->id === $item->id && $cartItem->options->type === $type;
        })->count();

        if (!$in_compare && Cart::instance('compare')->count() < 3) {
            Cart::instance('compare')->add(
                $item->id,
                [
                    'en' => $item->getTranslation('name', 'en'),
                    'ar' => $item->getTranslation('name', 'ar'),
                ],
                1,
                $item->best_price,
                [
                    'type'=>$type,
                    'thumbnail' => $item->thumbnail ?? null,
                    "slug" => $item->slug ?? ""
                ]
            )->associate($type == "Product" ? Product::class : Collection::class);

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
        } else {
            ############ Emit Sweet Alert :: Start ############
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('front/homePage.This product Is Already In The Comparison'),
                'icon' => 'error'
            ]);
            ############ Emit Sweet Alert :: End ############
        }
        ############ Add Product to Compare :: End ############

        ############ Emit event to reinitialize the slider :: Start ############
        $this->emit('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Add To Compare :: End ##############
}
