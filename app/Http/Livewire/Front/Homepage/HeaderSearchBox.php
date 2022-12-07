<?php

namespace App\Http\Livewire\Front\Homepage;

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
        if ($this->search) {
            $products = Product::select([
                'id',
                'name',
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
                )
                ->where(
                    fn ($q) =>
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('barcode', 'like', '%' . $this->search . '%')
                        ->orWhere('original_price', 'like', '%' . $this->search . '%')
                        ->orWhere('base_price', 'like', '%' . $this->search . '%')
                        ->orWhere('final_price', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhere('model', 'like', '%' . $this->search . '%')
                        ->orWhereHas('brand', fn ($q) => $q->where('brands.name', 'like', '%' . $this->search . '%'))
                )
                ->take(10)
                ->get();

            $collections = Collection::select([
                'id',
                'name',
                'barcode',
                'original_price',
                'base_price',
                'final_price',
                'under_reviewing',
                'points',
                'description',
                'model'
            ])
                ->where(
                    fn ($q) =>
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('barcode', 'like', '%' . $this->search . '%')
                        ->orWhere('original_price', 'like', '%' . $this->search . '%')
                        ->orWhere('base_price', 'like', '%' . $this->search . '%')
                        ->orWhere('final_price', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhere('model', 'like', '%' . $this->search . '%')
                )
                ->take(10)
                ->get();

            $this->items = $collections->concat($products)->map(function ($product_collection) {
                $product_collection->product_collection = class_basename($product_collection);
                return $product_collection;
            })->toArray();
        }
    }
}
