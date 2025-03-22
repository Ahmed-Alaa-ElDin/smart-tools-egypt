<?php

namespace App\Livewire\Admin\Products;

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
    public $productsIds = [];
    public $selectAllProducts = false;

    protected $listeners = [
        'unselectAll',
    ];

    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');
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
            ->with('subcategories', 'thumbnail')
            ->leftJoin('brands', 'brand_id', '=', 'brands.id')
            ->where(function ($q) {
                $q
                    ->where('products.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('products.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('products.base_price', 'like', '%' . $this->search . '%')
                    ->orWhere('products.final_price', 'like', '%' . $this->search . '%');
            })
            ->where('publish', 1)

            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage, ['*'], 'ProductsPage');

        $this->productsIds = $products->pluck('id')->toArray();

        $this->checkAllProductsSelected();

        return view('livewire.admin.products.product-list-datatable', compact('products'));
    }

    // reset pagination after new search
    public function updatingSearch()
    {
        $this->resetPage('ProductsPage');
    }

    // Add conditions of sorting
    public function setSortBy($field)
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
        $this->dispatch('selectedProductsUpdated', $this->selectedProducts);
    }

    public function unselectAll()
    {
        $this->selectedProducts = [];
    }

    public function updatedSelectAllProducts($value)
    {
        if ($value) {
            $this->selectedProducts = array_merge($this->selectedProducts, $this->productsIds);
        } else {
            $this->selectedProducts = array_diff($this->selectedProducts, $this->productsIds);
        }

        $this->dispatch('selectedProductsUpdated', $this->selectedProducts);
    }

    private function checkAllProductsSelected()
    {
        $this->selectAllProducts = count(array_diff($this->productsIds, $this->selectedProducts)) == 0 && count($this->productsIds) > 0 ? true : false;
    }
}
