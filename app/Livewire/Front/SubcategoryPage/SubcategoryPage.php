<?php

namespace App\Livewire\Front\SubcategoryPage;

use App\Models\Product;
use App\Livewire\Front\ProductFilter\ProductFilter;
use Illuminate\Support\Collection as SupportCollection;

class SubcategoryPage extends ProductFilter
{
    public $subcategoryId;

    /**
     * Returns a collection of id's of the given model that match the search query.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getBaseQuery(): SupportCollection
    {
        $products = $this->getSearchResults(Product::class, ['brand']);

        return $products
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

        $query->where(fn($q) => $this->applySearchConditions($q, $model));

        return $query->pluck('id')->pipe(
            $model === Product::class ? 'getBestOfferForProducts' : 'getBestOfferForCollections'
        );
    }

    /**
     * Applies the search conditions to the given query for the given model.
     */
    private function applySearchConditions($query, string $model): void
    {
        $query->whereHas('subcategories', function ($query) {
            $query->where('subcategories.id', $this->subcategoryId);
        });
    }
}
