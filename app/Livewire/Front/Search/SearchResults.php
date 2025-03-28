<?php

namespace App\Livewire\Front\Search;

use App\Models\Product;
use App\Models\Collection;
use App\Livewire\Front\ProductFilter\ProductFilter;
use Illuminate\Support\Collection as SupportCollection;

class SearchResults extends ProductFilter
{
    /**
     * Returns a collection of id's of the given model that match the search query.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getBaseQuery(): SupportCollection
    {
        $products = $this->getSearchResults(Product::class, ['brand']);
        $collections = $this->getSearchResults(Collection::class);

        return $collections->concat($products)
            ->sortBy([[$this->sort_by, $this->direction]])
            ->map(fn($item) => tap($item, fn($i) => $i->product_collection = class_basename($i)));
    }

    /**
     * Return a collection of id's of the given model that match the search query.
     *
     * @param string $model
     * @param array $relations
     * @return \Illuminate\Support\Collection
     */
    private function getSearchResults(string $model, array $relations = []): SupportCollection
    {
        $query = $model::query()->select('id');

        if ($relations) {
            $query->with($relations);
        }

        if ($this->search) {
            $query->where(fn($q) => $this->applySearchConditions($q, $model));
        }

        return $query->pluck('id')->pipe(
            $model === Product::class ? 'getBestOfferForProducts' : 'getBestOfferForCollections'
        );
    }

    /**
     * Applies the search conditions to the given query for the given model.
     *
     * The conditions applied are:
     * - MATCH(name,description) AGAINST(?) with the given search query
     * - name LIKE %{$search}%
     * - model LIKE %{$search}%
     * - If the model is Product, the brand.name LIKE %{$search}%
     */
    private function applySearchConditions($query, string $model): void
    {
        $query->whereRaw("MATCH(name,description) AGAINST(?)", [trim($this->search)])
            ->orWhere('name', 'like', "%{$this->search}%")
            ->orWhere('model', 'like', "%{$this->search}%");

        if ($model === Product::class) {
            $query->orWhereHas('brand', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
        }
    }
}
