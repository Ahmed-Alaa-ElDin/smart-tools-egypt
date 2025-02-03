<?php

namespace App\Livewire\Front\ProductFilter;

use App\Models\Product;
use Livewire\Component;
use App\Models\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Collection as SupportCollection;

abstract class ProductFilter extends Component
{
    use WithPagination;

    public $perPage;
    public $sort_by;
    public $direction;
    public $filters;
    public $search;
    public $brands;
    public $selectedBrands = [];
    public $subcategories;
    public $selectedSubcategories = [];
    public $categories;
    public $selectedCategories = [];
    public $supercategories;
    public $selectedSupercategories = [];
    public $minPrice;
    public $maxPrice;
    public $currentMinPrice;
    public $currentMaxPrice;
    public $currentRating;
    public $oneRatingNo;
    public $twoRatingNo;
    public $threeRatingNo;
    public $fourRatingNo;
    public $fiveRatingNo;
    public $currentFreeShipping;
    public $currentReturnable;
    public $currentAvailable;
    public $sectionTitle;

    protected $queryString = ['search', 'sort_by', 'direction'];

    abstract protected function getBaseQuery(): SupportCollection;

    public function mount()
    {
        $this->perPage = config('settings.front_pagination');
    }

    public function render()
    {
        $totalItems = $this->getBaseQuery();

        $this->setupPriceRange($totalItems);
        $this->setupRatingCounts($totalItems);
        $this->setupFilterOptions($totalItems);
        $this->checkActiveFilters();

        $filteredItems = $this->applyFilters($totalItems);
        $items = $filteredItems->paginate($this->perPage);

        return view('livewire.front.product-filter.product-filter', [
            'items' => $items,
        ]);
    }

    /**
     * Returns a collection of id's of the given model that match the search query.
     *
     * @return \Illuminate\Support\Collection
     */
    public function search(): SupportCollection
    {
        $products = $this->getSearchResults(Product::class, ['brand']);
        $collections = $this->getSearchResults(Collection::class);

        return $collections->concat($products)
            ->sortBy([[$this->sort_by, $this->direction]])
            ->map(fn($item) => tap($item, fn($i) => $i->product_collection = class_basename($i)));
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
            $model == "App\Models\Product" ? 'getBestOfferForProducts' : 'getBestOfferForCollections'
        );
    }

    /**
     * Setup the price range by setting the min and max values
     * based on the given items collection.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @return void
     */
    private function setupPriceRange(SupportCollection $items): void
    {
        $this->minPrice = round($items->min('final_price'), 2);
        $this->maxPrice = round($items->max('final_price'), 2);

        $this->currentMinPrice ??= $this->minPrice;
        $this->currentMaxPrice ??= $this->maxPrice;
    }

    /**
     * Setup the rating counts by calculating the number of items
     * with average ratings of 1 through 5 from the given collection.
     *
     * @param \Illuminate\Support\Collection $items
     * @return void
     */
    private function setupRatingCounts(SupportCollection $items): void
    {
        $this->fiveRatingNo = $items->where('avg_rating', 5)->count();
        $this->fourRatingNo = $items->where('avg_rating', '>=', 4)->count();
        $this->threeRatingNo = $items->where('avg_rating', '>=', 3)->count();
        $this->twoRatingNo = $items->where('avg_rating', '>=', 2)->count();
        $this->oneRatingNo = $items->where('avg_rating', '>=', 1)->count();
    }

    /**
     * Set up the filter options by retrieving the
     * available brands, subcategories, categories, and
     * supercategories from the given items collection.
     *
     * @param \Illuminate\Support\Collection $items
     * @return void
     */
    private function setupFilterOptions(SupportCollection $items): void
    {
        $this->brands = $this->getBrands($items)->toArray();
        $this->subcategories = $this->getSubcategories($items)->toArray();
        $this->categories = $this->getCategories($items)->toArray();
        $this->supercategories = $this->getSupercategories($items)->toArray();
    }

    public function changeDirection()
    {
        $this->direction = $this->direction == 'asc' ? 'desc' : 'asc';
    }

    public function getBrands(SupportCollection $items): SupportCollection
    {
        return $this->getFilterData($items, 'brand');
    }

    public function getSubcategories(SupportCollection $items): SupportCollection
    {
        return $this->getFilterData($items, 'subcategories', true);
    }

    public function getCategories(SupportCollection $items): SupportCollection
    {
        return $this->getFilterData($items, 'categories', true);
    }

    public function getSupercategories(SupportCollection $items): SupportCollection
    {
        return $this->getFilterData($items, 'supercategories', true);
    }

    private function getFilterData(
        SupportCollection $items,
        string $relation,
        bool $uniqueSubitems = false
    ): SupportCollection {
        $items = $items->whereNotNull($relation)->pluck($relation);

        if ($uniqueSubitems) {
            $items = $items->flatMap(fn($subitems) => $subitems->unique('id'));
        }

        return $items->countBy('id')
            ->map(fn($count, $id) => tap($items->firstWhere('id', $id), fn($item) => $item->count = $count))
            ->sortByDesc('count')
            ->values();
    }

    private function getNestedFilterData(
        SupportCollection $items,
        string $nestedRelation,
    ): SupportCollection {
        return $items->whereNotNull($nestedRelation);
            // ->pluck($nestedRelation)
            // ->unique('id')
            // ->countBy('id')
            // ->map(fn($count, $id) => tap($items->pluck($nestedRelation)->firstWhere('id', $id), fn($item) => $item->count = $count))
            // ->sortByDesc('count')
            // ->values();
    }

    // Filter Management
    public function checkActiveFilters(): void
    {
        $this->filters = collect([
            $this->selectedSupercategories,
            $this->selectedCategories,
            $this->selectedSubcategories,
            $this->selectedBrands,
            $this->currentRating,
            $this->currentAvailable,
            $this->currentReturnable,
            $this->currentFreeShipping,
            $this->currentMinPrice != $this->minPrice,
            $this->currentMaxPrice != $this->maxPrice,
        ])->contains(fn($value) => !empty($value));
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

    private function applyFilters(SupportCollection $items): SupportCollection
    {
        if (!$this->filters) {
            return $items;
        }

        return $items->filter(function ($item) {
            return $this->passesSupercategoryFilter($item)
                && $this->passesCategoryFilter($item)
                && $this->passesSubcategoryFilter($item)
                && $this->passesBrandFilter($item)
                && $this->passesRatingFilter($item)
                && $this->passesAvailabilityFilter($item)
                && $this->passesReturnableFilter($item)
                && $this->passesFreeShippingFilter($item)
                && $this->passesPriceFilter($item);
        });
    }

    // Filter Check Methods
    private function passesSupercategoryFilter($item): bool
    {
        if (empty($this->selectedSupercategories)) return true;

        $supercategoryIds = $item->subcategories
            ?->pluck('category.supercategory.id')
            ->toArray() ?? [];

        return !empty(array_intersect($supercategoryIds, $this->selectedSupercategories));
    }

    private function passesCategoryFilter($item): bool
    {
        if (empty($this->selectedCategories)) return true;

        $categoryIds = $item->subcategories
            ?->pluck('category.id')
            ->toArray() ?? [];

        return !empty(array_intersect($categoryIds, $this->selectedCategories));
    }

    private function passesSubcategoryFilter($item): bool
    {
        if (empty($this->selectedSubcategories)) return true;

        $subcategoryIds = $item->subcategories
            ?->pluck('id')
            ->toArray() ?? [];

        return !empty(array_intersect($subcategoryIds, $this->selectedSubcategories));
    }

    private function passesBrandFilter($item): bool
    {
        return empty($this->selectedBrands) || in_array($item->brand_id, $this->selectedBrands);
    }

    private function passesRatingFilter($item): bool
    {
        return is_null($this->currentRating) || $item->avg_rating >= $this->currentRating;
    }

    private function passesAvailabilityFilter($item): bool
    {
        return !$this->currentAvailable || $item->quantity > 0;
    }

    private function passesReturnableFilter($item): bool
    {
        return !$this->currentReturnable || $item->refundable;
    }

    private function passesFreeShippingFilter($item): bool
    {
        return !$this->currentFreeShipping || $item->free_shipping;
    }

    private function passesPriceFilter($item): bool
    {
        $price = $item->final_price;
        return $price >= $this->currentMinPrice && $price <= $this->currentMaxPrice;
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

    public function loadMore()
    {
        $this->perPage += config('settings.front_pagination');
    }
}
