<?php

namespace App\Http\Livewire\Admin\Brands;

use App\Models\Brand;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class DeletedBrandsDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = [
        'forceDeleteBrand', 'restoreBrand', 'forceDeleteAllBrands', 'restoreAllBrands'
    ];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'name';
    }

    // Render With each update
    public function render()
    {
        $brands = Brand::onlyTrashed()
            ->select([
                'id',
                'name',
                'logo_path',
                'country_id',
            ])->with('country')
            ->withCount('products')
            ->where(function ($q) {
                return $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('country', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortBy, $this->sortDirection)->paginate($this->perPage);


        return view('livewire.admin.brands.deleted-brands-datatable', compact('brands'));
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
        return $this->sortBy = $field;
    }

    ######## Force Delete #########
    public function deleteConfirm($brand_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to permanently delete this brand ?'),
            'confirmButtonText' => __('admin/productsPages.Delete'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'forceDeleteBrand',
            'id' => $brand_id,
        ]);
    }

    public function forceDeleteBrand($brand_id)
    {
        try {
            $brand = Brand::withTrashed()->findOrFail($brand_id);
            $brand->forceDelete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Brand has been deleted permanently successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Brand hasn't been deleted permanently"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Force Delete #########

    ######## Restore #########
    public function restoreConfirm($brand_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to restore this brand ?'),
            'confirmButtonText' => __('admin/productsPages.Restore'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'red',
            'confirmButtonColor' => 'green',
            'focusDeny' => false,
            'icon' => 'warning',
            'method' => 'restoreBrand',
            'id' => $brand_id,
        ]);
    }

    public function restoreBrand($brand_id)
    {
        try {
            $brand = Brand::withTrashed()->findOrFail($brand_id);
            $brand->restore();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Brand has been restored successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Brand hasn't been restored"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Restore #########

    ######## Force Delete All #########
    public function forceDeleteAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to delete all brands permanently ?'),
            'confirmButtonText' => __('admin/productsPages.Delete'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'forceDeleteAllBrands',
            'id' => ''
        ]);
    }

    public function forceDeleteAllBrands()
    {
        try {
            Brand::onlyTrashed()->forceDelete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.All brands have been deleted successfully'),
                'icon' => 'info'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.All brands haven\'t been deleted'),
                'icon' => 'error'
            ]);
        }
    }
    ######## Force Delete All #########

    ######## Restore All #########

    public function restoreAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to restore all brands ?'),
            'confirmButtonText' => __('admin/productsPages.Restore'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'red',
            'confirmButtonColor' => 'green',
            'focusDeny' => false,
            'icon' => 'warning',
            'method' => 'restoreAllBrands',
            'id' => '',
        ]);
    }

    public function restoreAllBrands()
    {
        try {
            Brand::onlyTrashed()->restore();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.All brands have been restored successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.All brands haven\'t been restored'),
                'icon' => 'error'
            ]);
        }
    }
    ######## Restore All #########

}
