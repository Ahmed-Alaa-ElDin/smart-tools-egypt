<?php

namespace App\Livewire\Front\Products;

use Livewire\Component;

class GeneralProductsList extends Component
{
    public $productsIds = [];
    public $collectionsIds = [];
    public $perPage = 0;

    public $allItems = []; // Store all items

    public function mount()
    {
        $this->perPage = config('settings.front_pagination');
        $this->loadAllItems();
    }

    private function loadAllItems()
    {
        if (empty($this->allItems)) {
            ############ Get Best Offer for all products :: Start ############
            $products = getBestOfferForProducts(
                $this->productsIds
            )->map(function ($product) {
                $product->type = "Product";
                return $product->toArray(); // Convert to array
            });
            ############ Get Best Offer for all products :: End ############

            ############ Get Best Offer for all collections :: Start ############
            $collections = getBestOfferForCollections(
                $this->collectionsIds
            )->map(function ($collection) {
                $collection->type = "Collection";
                return $collection->toArray(); // Convert to array
            });
            ############ Get Best Offer for all collections :: End ############

            $this->allItems = $collections->concat($products)->shuffle()->toArray();
        }
    }

    public function render()
    {
        ############ Concatenation of best Products & Collections  :: Start ############
        $items = collect($this->allItems)->paginate($this->perPage);
        ############ Concatenation of best Products & Collections  :: End ############

        return view('livewire.front.products.general-products-list', compact('items'));
    }

    // Load more products
    public function loadMore()
    {
        $this->perPage += config('settings.front_pagination');
    }
}
