<?php

namespace App\Livewire\Front\Homepage;

use App\Models\Collection;
use App\Models\Product;
use Livewire\Component;

class HeaderSearchBox extends Component
{
    public $items = [], $search = "";

    public function render()
    {
        return view('livewire.front.homepage.header-search-box');
    }

    public function updatedSearch($key)
    {
        $this->items = Product::select([
            'id',
            'name',
            'slug',
            'barcode',
            'original_price',
            'base_price',
            'final_price',
            'under_reviewing',
            'points',
            'description',
            'model',
            'brand_id'
        ])
            ->with(
                'brand',
                'thumbnail'
            )->first();
        // if ($this->search) {
        //     $products = Product::select([
        //         'id',
        //         'name',
        //         'slug',
        //         'barcode',
        //         'original_price',
        //         'base_price',
        //         'final_price',
        //         'under_reviewing',
        //         'points',
        //         'description',
        //         'model',
        //         'brand_id'
        //     ])
        //         ->with(
        //             'brand',
        //             'thumbnail'
        //         )
        //         ->where(
        //             fn ($q) =>
        //             $q->whereRaw(
        //                 "MATCH(name,description) AGAINST(?)",
        //                 array(trim($this->search))
        //             )->orWhere('barcode', 'like', '%' . $this->search . '%')
        //                 ->orWhere('name', 'like', '%' . $this->search . '%')
        //                 ->orWhere('original_price', 'like', '%' . $this->search . '%')
        //                 ->orWhere('base_price', 'like', '%' . $this->search . '%')
        //                 ->orWhere('final_price', 'like', '%' . $this->search . '%')
        //                 ->orWhere('description', 'like', '%' . $this->search . '%')
        //                 ->orWhere('model', 'like', '%' . $this->search . '%')
        //                 ->orWhereHas('brand', fn ($q) => $q->where('brands.name', 'like', '%' . $this->search . '%'))
        //         )
        //         ->where('publish', 1)
        //         ->take(value: 10)
        //         ->get();

        //     $collections = Collection::select([
        //         'id',
        //         'name',
        //         'slug',
        //         'barcode',
        //         'original_price',
        //         'base_price',
        //         'final_price',
        //         'under_reviewing',
        //         'points',
        //         'description',
        //         'model'
        //     ])
        //         ->with('thumbnail')
        //         ->where(
        //             fn ($q) =>
        //             $q->whereRaw(
        //                 "MATCH(name,description) AGAINST(?)",
        //                 array(trim($this->search))
        //             )->orWhere('barcode', 'like', '%' . $this->search . '%')
        //             ->orWhere('name', 'like', '%' . $this->search . '%')
        //                 ->orWhere('original_price', 'like', '%' . $this->search . '%')
        //                 ->orWhere('base_price', 'like', '%' . $this->search . '%')
        //                 ->orWhere('final_price', 'like', '%' . $this->search . '%')
        //                 ->orWhere('description', 'like', '%' . $this->search . '%')
        //                 ->orWhere('model', 'like', '%' . $this->search . '%')
        //         )
        //         ->where('publish', 1)
        //         ->take(10)
        //         ->get();

        //     $this->items = $collections->concat($products)->map(function ($product_collection) {
        //         $product_collection->product_collection = class_basename($product_collection);
        //         return $product_collection;
        //     })->toArray();
        // } else {
        //     $this->items = collect([]);
        // }
    }

    public function seeMore()
    {
        if ($this->search) {
            redirect(route('front.search', ['search' => $this->search]));
        }
    }
}
