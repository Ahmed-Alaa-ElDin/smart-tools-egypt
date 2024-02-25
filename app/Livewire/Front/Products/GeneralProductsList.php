<?php

namespace App\Livewire\Front\Products;

use Livewire\Component;

class GeneralProductsList extends Component
{
    public $productsIds = [];
    public $collectionsIds = [];
    public $perPage = 0;

    public function mount()
    {
        $this->perPage = config('settings.front_pagination');
    }

    public function render()
    {
        ############ Get Best Offer for all products :: Start ############
        $products = getBestOfferForProducts(
            $this->productsIds
        )->map(function ($product) {
            $product->type = "Product";
            return $product;
        });
        ############ Get Best Offer for all products :: End ############

        ############ Get Best Offer for all collections :: Start ############
        $collections = getBestOfferForCollections(
            $this->collectionsIds
        )->map(function ($collection) {
            $collection->type = "Collection";
            return $collection;
        });
        ############ Get Best Offer for all collections :: End ############

        ############ Concatenation of best Products & Collections  :: Start ############
        $items = $collections->concat($products)->paginate($this->perPage);
        ############ Concatenation of best Products & Collections  :: End ############

        return view('livewire.front.products.general-products-list', compact('items'));
    }

    // Load more products
    public function loadMore()
    {
        $this->perPage += config('settings.front_pagination');
    }
}
