<?php

namespace App\Http\Livewire\Front\Comparison;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Collection;

class ComparisonItems extends Component
{
    use WithPagination;

    protected $listeners = ['cartUpdated' => 'render'];

    public function render()
    {
        $items = Cart::instance('compare')->content();

        // get the best offer for each item
        $items = $items->map(function ($item) {
            if ($item->options->type == 'Product') {
                $item = getBestOfferForProduct($item->id);
                $item->type = 'Product';
            } elseif ($item->options->type == 'Collection') {
                $item = getBestOfferForCollection($item->id);
                $item->type = 'Collection';
            }
            return $item;
        });

        // dd($items);

        return view('livewire.front.comparison.comparison-items', compact('items'));
    }
}
