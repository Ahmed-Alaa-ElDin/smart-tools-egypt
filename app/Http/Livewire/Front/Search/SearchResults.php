<?php

namespace App\Http\Livewire\Front\Search;

use App\Models\Collection;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class SearchResults extends Component
{
    use WithPagination;

    protected $queryString = ['sort_by', 'direction', 'filter'];

    private $perPage;
    public $sort_by, $direction, $filter;
    public $search;

    public function render()
    {
        $this->perPage = config('constants.constants.FRONT_PAGINATION');

        $items = $this->search();

        return view('livewire.front.search.search-results', ['items' => $items]);
    }

    public function changeDirection()
    {
        $this->direction = $this->direction == 'asc' ? 'desc' : 'asc';
    }

    public function search()
    {
        if ($this->search) {
            $productsIds = Product::select([
                'id',
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
                ->pluck('id');

            $products = getBestOfferForProducts($productsIds);

            $collectionsIds = Collection::select([
                'id',
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
                ->pluck('id');

            $collections = getBestOfferForCollections($collectionsIds);

            $items = $collections->concat($products)->sortBy([[$this->sort_by , $this->direction]])->map(function ($product_collection) {
                $product_collection->product_collection = class_basename($product_collection);
                return $product_collection;
            })->paginate($this->perPage);
        } else {
            $items = collect([]);
        }

        // dd($items);
        return $items;
    }
}
