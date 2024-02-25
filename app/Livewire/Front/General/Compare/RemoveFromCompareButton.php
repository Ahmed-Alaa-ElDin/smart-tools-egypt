<?php

namespace App\Livewire\Front\General\Compare;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class RemoveFromCompareButton extends Component
{
    public $item_id, $type, $text = false, $large = false, $remove = false;

    public function render()
    {
        return view('livewire.front.general.compare.remove-from-compare-button');
    }

    ############## Remove From Comparison :: Start ##############
    public function removeFromComparison($item_id, $type)
    {
        Cart::instance('compare')->search(function ($cartItem, $rowId) use ($item_id, $type) {
            return $cartItem->id === $item_id && $cartItem->options->type === $type;
        })->each(function ($cartItem, $rowId) {
            Cart::instance('compare')->remove($rowId);
        });

        if (auth()->check()) {
            Cart::instance('compare')->store(auth()->user()->id);
        }

        ############ Emit Sweet Alert :: Start ############
        $this->dispatch('swalDone', text: __('front/homePage.Product Has Been Removed From the Comparison Successfully'),
            icon:'success');
        ############ Emit Sweet Alert :: End ############

        ############ Emit event to reinitialize the slider :: Start ############
        $this->dispatch('cartUpdated');
        ############ Emit event to reinitialize the slider :: End ############
    }
    ############## Remove From Comparison :: End ##############

}
