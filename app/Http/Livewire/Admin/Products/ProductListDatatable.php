<?php

namespace App\Http\Livewire\Admin\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Config;

class ProductListDatatable extends Component
{
    use WithPagination;

    public $search = "";
    public $perPage;
    public $sortBy;
    public $sortDirection;
    public $selectedProducts = [];
    public $subcategory_id = "%";
    public $brand_id = "%";
    public $excludedProducts = [];

    protected $listeners = [
        'unselectAll',
    ];

    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');
        $this->sortBy = 'products.name->' . session('locale');
        $this->sortDirection = 'ASC';
    }

    public function render()
    {
        $products = Product::select([
            'products.id',
            'products.name',
            'brand_id',
            'slug',
            'quantity',
            'low_stock',
            'original_price',
            'base_price',
            'final_price',
            'points',
            'publish',
            'under_reviewing',
            'brands.name as brand_name'
        ])
            ->with('subcategories', 'brand', 'thumbnail')
            ->leftJoin('brands', 'brand_id', '=', 'brands.id')
            ->where(function ($q) {
                $q
                    ->where('products.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('products.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('products.base_price', 'like', '%' . $this->search . '%')
                    ->orWhere('products.final_price', 'like', '%' . $this->search . '%');
            })
            ->whereNotIn('products.id', $this->excludedProducts)
            ->where('publish', 1)

            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage, ['*'], 'ProductsPage');

        return view('livewire.admin.products.product-list-datatable', compact('products'));
    }

    // reset pagination after new search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Add conditions of sorting
    public function sortBy($field)
    {
        if ($this->sortDirection == 'ASC') {
            $this->sortDirection = 'DESC';
        } else {
            $this->sortDirection = 'ASC';
        }
        if ($field == 'name') {
            return $this->sortBy = 'name->' . session('locale');
        }
        return $this->sortBy = $field;
    }

    public function updatedSelectedProducts()
    {
        $this->emit('selectedProductsUpdated', $this->selectedProducts);
    }

    public function unselectAll()
    {
        $this->selectedProducts = [];
    }
}
