<?php

namespace App\Http\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class ProductsDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    public $selectedProducts = [];

    protected $listeners = ['softDeleteProduct', 'softDeleteAllProduct', 'publishAllProduct', 'hideAllProduct'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'products.name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $products = Product::select([
                'products.id',
                'products.name',
                'brand_id',
                'slug',
                'quantity',
                'low_stock',
                'base_price',
                'final_price',
                'points',
                'publish',
                'under_reviewing',
                'brands.name as brand_name'
            ])
            ->with('subcategories', 'brand', 'thumbnail')
            ->leftJoin('brands', 'brand_id', '=', 'brands.id')
            ->where('products.name->en', 'like', '%' . $this->search . '%')
            ->orWhere('products.name->ar', 'like', '%' . $this->search . '%')
            ->orWhere('products.base_price', 'like', '%' . $this->search . '%')
            ->orWhere('products.final_price', 'like', '%' . $this->search . '%')
            ->orWhereHas('subcategories', function ($query) {
                $query->where('name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('name->ar', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('brand', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.products.products-datatable', compact('products'));
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

    public function publish($product_id)
    {
        $product = Product::findOrFail($product_id);

        try {
            $product->update([
                'publish' => $product->publish ? 0 : 1
            ]);

            $this->dispatchBrowserEvent('swalDone', [
                "text" => $product->publish ? __('admin/productsPages.Product has been published') : __('admin/productsPages.Product has been hidden'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => $product->publish ? __("admin/productsPages.Product hasn't been published") : __("admin/productsPages.Product hasn't been hidden"),
                'icon' => 'error'
            ]);
        }
    }

    ######## Deleted #########
    public function deleteConfirm($product_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to delete this product ?'),
            'confirmButtonText' => __('admin/productsPages.Delete'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'confirmButtonColor' => 'red',
            'func' => 'softDeleteProduct',
            'product_id' => $product_id,
        ]);
    }

    public function softDeleteProduct($product_id)
    {
        try {
            $product = Product::findOrFail($product_id);
            $product->delete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Product has been deleted successfully'),
                'icon' => 'success'
            ]);

            $this->selectedProducts = [];
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Product hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Deleted #########


    ######## Unselect All Products #########
    public function unselectAll()
    {
        $this->selectedProducts = [];
    }
    ######## Unselect All Products #########

    ######## Deleted All Selected Products #########
    public function deleteAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to delete all selected products ?'),
            'confirmButtonText' => __('admin/productsPages.Delete'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'confirmButtonColor' => 'red',
            'func' => 'softDeleteAllProduct',
            'product_id' => '',
        ]);
    }

    public function softDeleteAllProduct()
    {
        try {
            foreach ($this->selectedProducts as $key => $selectedProduct) {

                $product = Product::findOrFail($selectedProduct);
                $product->delete();
            }

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Products have been deleted successfully'),
                'icon' => 'success'
            ]);

            $this->selectedProducts = [];
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Products haven't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Deleted All Selected Products #########


    ######## Publish All Selected Products #########
    public function publishAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to publish all selected products ?'),
            'confirmButtonText' => __('admin/productsPages.Publish'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'confirmButtonColor' => 'green',
            'func' => 'publishAllProduct',
            'product_id' => '',
        ]);
    }

    public function publishAllProduct()
    {

        try {
            foreach ($this->selectedProducts as $key => $selectedProduct) {
                $product = Product::findOrFail($selectedProduct);

                $product->update([
                    'publish' => 1
                ]);
            }

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Products have been published'),
                'icon' => 'success'
            ]);

            $this->selectedProducts = [];
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Products haven't been published"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Publish All Selected Products #########

    ######## Hide All Selected Products #########
    public function hideAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to hide all selected products ?'),
            'confirmButtonText' => __('admin/productsPages.Hide'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'confirmButtonColor' => 'red',
            'func' => 'hideAllProduct',
            'product_id' => '',
        ]);
    }

    public function hideAllProduct()
    {

        try {
            foreach ($this->selectedProducts as $key => $selectedProduct) {
                $product = Product::findOrFail($selectedProduct);

                $product->update([
                    'publish' => 0
                ]);
            }

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Products have been hidden'),
                'icon' => 'success'
            ]);

            $this->selectedProducts = [];
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Products haven't been hidden"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Hide All Selected Products #########
}
