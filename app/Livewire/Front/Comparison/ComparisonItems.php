<?php

namespace App\Livewire\Front\Comparison;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class ComparisonItems extends Component
{
    // Listen to the event named 'cartUpdated' from the component named 'cart-items'
    protected $listeners = ['cartUpdated' => 'render'];

    // Render the component
    public function render()
    {
        // get the items from the comparison list
        $items = $this->getItems();

        $specs = $this->getSpecs($items);

        return view('livewire.front.comparison.comparison-items', compact('items', 'specs'));
    }

    /**
     * Get the items from the comparison list
     *  - get the best offer for each item
     *  - add the type of the item (Product or Collection)
     *  - return the items
     *  - @return array $items
     */
    private function getItems()
    {
        $items = Cart::instance('compare')->content();

        // get all products ids
        $productsIds = $items->where('options.type', 'Product')->pluck('id')->toArray();

        // get best offer for the products
        $products = getBestOfferForProducts($productsIds);


        // get all collections ids
        $collectionsIds = $items->where('options.type', 'Collection')->pluck('id')->toArray();

        // get best offer for the collections
        $collections = getBestOfferForCollections($collectionsIds);

        // get the best offer for each item
        $items = $items->map(function ($item) use ($products, $collections) {
            if ($item->options->type == 'Product') {
                $item = $products->where('id', $item->id)->first();
                $item->type = 'Product';
            } elseif ($item->options->type == 'Collection') {
                $item = $collections->where('id', $item->id)->first();
                $item->type = 'Collection';
            }
            return $item;
        });

        return $items;
    }

    /**
     * Get the specs of the items
     *  - extract the specs from the items
     *  - get the unique specs (ar,en)
     *  - return the specs
     *  - @return array $specs
     */
    private function getSpecs($items)
    {
        // extract the specs from the items
        $specs = $items->mapWithKeys(function ($item) {
            $specs = $item->type == 'Product' ? $item->specs : collect([]);

            return [$item->id => $specs];
        });

        $allSpecs = [];
        $productsIds = $specs->keys();

        // get the unique specs according to the current locale
        $specs->map(function ($productSpecs, $productId) use (&$allSpecs, $productsIds) {
            $locale = session("locale");

            foreach ($productSpecs as $spec) {
                if (!isset($allSpecs[$spec->getTranslation("title", $locale)])) {
                    foreach ($productsIds as $id) {
                        $allSpecs[$spec->getTranslation("title", $locale)][$id] = "";
                    }
                }

                $allSpecs[$spec->getTranslation("title", $locale)][$productId] = $spec->getTranslation("value", $locale);
            }
        });

        return $allSpecs;
    }
}
