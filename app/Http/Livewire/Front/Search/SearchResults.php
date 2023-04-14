<?php

namespace App\Http\Livewire\Front\Search;

use App\Models\Collection;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class SearchResults extends Component
{
    use WithPagination;

    protected $queryString = ['sort_by', 'direction'];

    public $totalItems;
    public $perPage;
    public $sort_by, $direction, $filters;
    public $search;
    public $brands, $selectedBrands = [];
    public $subcategories, $selectedSubcategories = [];
    public $categories, $selectedCategories = [];
    public $supercategories, $selectedSupercategories = [];
    public $minPrice, $maxPrice, $currentMinPrice, $currentMaxPrice;
    public $currentRating, $oneRatingNo, $twoRatingNo, $threeRatingNo, $fourRatingNo, $fiveRatingNo;
    public $currentFreeShipping, $currentReturnable, $currentAvailable;

    public function mount()
    {
        $this->perPage = config('constants.constants.FRONT_PAGINATION');
    }

    public function render()
    {
        $totalItems = $this->search();

        $this->minPrice = round($totalItems->min('final_price'), 2);
        $this->maxPrice = round($totalItems->max('final_price'), 2);

        if (is_null($this->currentMinPrice)) {
            $this->currentMinPrice = $this->minPrice;
        }

        if (is_null($this->currentMaxPrice)) {
            $this->currentMaxPrice = $this->maxPrice;
        }

        $this->fiveRatingNo = $totalItems->where('avg_rating', '=', 5)->count();
        $this->fourRatingNo = $totalItems->where('avg_rating', '>=', 4)->count();
        $this->threeRatingNo = $totalItems->where('avg_rating', '>=', 3)->count();
        $this->twoRatingNo = $totalItems->where('avg_rating', '>=', 2)->count();
        $this->oneRatingNo = $totalItems->where('avg_rating', '>=', 1)->count();

        $totalBrands = $this->getBrands($totalItems);
        $this->brands = $totalBrands->toArray();

        $totalSubcategories = $this->getSubcategories($totalItems);
        $this->subcategories = $totalSubcategories->toArray();

        $totalCategories = $this->getCategories($totalItems);
        $this->categories = $totalCategories->toArray();

        $totalSupercategories = $this->getSupercategories($totalItems);
        $this->supercategories = $totalSupercategories->toArray();

        $this->checkFilters();

        if ($this->filters) {
            $totalItemsAfterFilters = $totalItems->filter(function ($item) {
                // Supercategories
                if ($this->selectedSupercategories) {
                    $supercategoriesIds = $item->subcategories ? $item->subcategories->pluck('category.supercategory.id')->toArray() : [];

                    if (!count(array_intersect($supercategoriesIds, $this->selectedSupercategories))) {
                        return null;
                    }
                }

                // Categories
                if ($this->selectedCategories) {
                    $categoriesIds = $item->subcategories ? $item->subcategories->pluck('category.id')->toArray() : [];

                    if (!count(array_intersect($categoriesIds, $this->selectedCategories))) {
                        return null;
                    }
                }

                // Subcategories
                if ($this->selectedSubcategories) {
                    $subcategoriesIds = $item->subcategories ? $item->subcategories->pluck('id')->toArray() : [];

                    if (!count(array_intersect($subcategoriesIds, $this->selectedSubcategories))) {
                        return null;
                    }
                }

                // Brand
                if ($this->selectedBrands) {
                    if (!in_array($item->brand_id, $this->selectedBrands)) {
                        return null;
                    }
                }

                // Rating
                if (!is_null($this->currentRating)) {
                    if ($item->avg_rating < $this->currentRating) {
                        return null;
                    }
                }

                // Availability
                if ($this->currentAvailable) {
                    if ($item->quantity <= 0) {
                        return null;
                    }
                }

                // Returnable
                if ($this->currentReturnable) {
                    if (!$item->refundable) {
                        return null;
                    }
                }

                // Free Shopping
                if ($this->currentFreeShipping) {
                    if (!$item->free_shipping) {
                        return null;
                    }
                }

                // Min Price
                if ($this->currentMinPrice != $this->minPrice) {
                    if ($item->final_price < $this->currentMinPrice) {
                        return null;
                    }
                }

                // Min Price
                if ($this->currentMaxPrice != $this->maxPrice) {
                    if ($item->final_price > $this->currentMaxPrice) {
                        return null;
                    }
                }

                return $item;
            });
        } else {
            $totalItemsAfterFilters = $totalItems;
        }

        $items = $totalItemsAfterFilters->paginate($this->perPage);

        return view('livewire.front.search.search-results', [
            'items' => $items,
        ]);
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
                    $q->whereRaw(
                        "MATCH(name,description) AGAINST(?)",
                        array(trim($this->search))
                    )
                        ->orWhere('name', 'like', '%' . $this->search . '%')
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
                    $q->whereRaw(
                        "MATCH(name,description) AGAINST(?)",
                        array(trim($this->search))
                    )
                    ->orWhere('name', 'like', '%' . $this->search . '%')
                        ->orWhere('barcode', 'like', '%' . $this->search . '%')
                        ->orWhere('original_price', 'like', '%' . $this->search . '%')
                        ->orWhere('base_price', 'like', '%' . $this->search . '%')
                        ->orWhere('final_price', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhere('model', 'like', '%' . $this->search . '%')
                )
                ->pluck('id');

            $collections = getBestOfferForCollections($collectionsIds);

            $items = $collections->concat($products)->sortBy([[$this->sort_by, $this->direction]])->map(function ($product_collection) {
                $product_collection->product_collection = class_basename($product_collection);
                return $product_collection;
            });
        } else {
            $items = collect([]);
        }

        return $items;
    }

    public function getBrands($totalItems)
    {
        $allBrands = $totalItems->whereNotNull('brand')->pluck('brand');
        $brandsCount = $allBrands->countBy('id')->toArray();
        $brands = $allBrands->unique()->map(function ($brand) use ($brandsCount) {
            $brand->count = $brandsCount[$brand->id];
            return $brand;
        })->sortByDesc('count')->values();

        return $brands;
    }

    public function getSubcategories($totalItems)
    {
        $allSubcategories = $totalItems->whereNotNull('subcategories')->pluck('subcategories')->map(fn ($item) => $item->unique('id'))->flatten();
        $subcategoriesCount = $allSubcategories->countBy('id')->toArray();
        $subcategories = $allSubcategories->unique('id')->map(function ($subcategories) use ($subcategoriesCount) {
            $subcategories->count = $subcategoriesCount[$subcategories->id];
            return $subcategories;
        })->sortByDesc('count')->values();

        return $subcategories;
    }

    public function getCategories($totalItems)
    {
        $allSubcategories = $totalItems->whereNotNull('subcategories')->pluck('subcategories')->map(fn ($item) => $item->unique('category.id'))->flatten();
        $allCategories = $allSubcategories->whereNotNull('category')->pluck('category');
        $categoriesCount = $allCategories->countBy('id')->toArray();
        $categories = $allCategories->unique('id')->map(function ($categories) use ($categoriesCount) {
            $categories->count = $categoriesCount[$categories->id];
            return $categories;
        })->sortByDesc('count')->values();

        return $categories;
    }

    public function getSupercategories($totalItems)
    {
        $allSubcategories = $totalItems->whereNotNull('subcategories')->pluck('subcategories')->map(fn ($item) => $item->unique('supercategory.id'))->flatten();
        $allSupercategories = $allSubcategories->whereNotNull('supercategory')->pluck('supercategory');
        $supercategoriesCount = $allSupercategories->countBy('id')->toArray();
        $supercategories = $allSupercategories->unique('id')->map(function ($supercategories) use ($supercategoriesCount) {
            $supercategories->count = $supercategoriesCount[$supercategories->id];
            return $supercategories;
        })->sortByDesc('count')->values();

        return $supercategories;
    }

    public function checkFilters()
    {
        if (
            $this->selectedSupercategories ||
            $this->selectedCategories ||
            $this->selectedSubcategories ||
            $this->selectedBrands ||
            !is_null($this->currentRating) ||
            $this->currentAvailable ||
            $this->currentReturnable ||
            $this->currentFreeShipping ||
            $this->currentMinPrice != $this->minPrice ||
            $this->currentMaxPrice != $this->maxPrice
        ) {
            $this->filters = true;
        } else {
            $this->filters = false;
        }
    }

    public function clearFilters()
    {
        $this->filters = false;

        $this->selectedSupercategories = [];
        $this->selectedCategories = [];
        $this->selectedSubcategories = [];
        $this->selectedBrands = [];
        $this->currentRating = null;
        $this->currentAvailable = null;
        $this->currentReturnable = null;
        $this->currentFreeShipping = null;
        $this->currentMinPrice = $this->minPrice;
        $this->currentMaxPrice = $this->maxPrice;
    }

    public function updatedCurrentMinPrice()
    {
        if ($this->currentMinPrice > $this->currentMaxPrice) {
            $this->currentMinPrice = $this->currentMaxPrice;
        }

        if ($this->currentMinPrice < $this->minPrice) {
            $this->currentMinPrice = $this->minPrice;
        }
    }

    public function updatedCurrentMaxPrice()
    {
        if ($this->currentMinPrice > $this->currentMaxPrice) {
            $this->currentMaxPrice = $this->currentMinPrice;
        }

        if ($this->currentMaxPrice > $this->maxPrice) {
            $this->currentMaxPrice = $this->maxPrice;
        }
    }
}
