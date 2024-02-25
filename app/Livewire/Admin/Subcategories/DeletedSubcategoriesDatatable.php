<?php

namespace App\Livewire\Admin\Subcategories;

use App\Models\Subcategory;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class DeletedSubcategoriesDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = [
        'forceDeleteSubcategory', 'restoreSubcategory', 'forceDeleteAllSubcategories', 'restoreAllSubcategories'
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
        $subcategories = Subcategory::onlyTrashed()
            ->select([
                'subcategories.*',
                'categories.id as category_id',
                'categories.name as category_name',
                'supercategories.id as supercategory_id',
                'supercategories.name as supercategory_name',
            ])->with([
                'supercategory', 'category'
            ])
            ->withCount('products')
            ->where(function ($q) {
                return $q->where('subcategories.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('subcategories.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('categories.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('categories.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('supercategories.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('supercategories.name->en', 'like', '%' . $this->search . '%');
            })
            ->leftJoin('categories', 'category_id', '=', 'categories.id')
            ->leftJoin('supercategories', 'categories.supercategory_id', '=', 'supercategories.id')
            ->orderBy($this->sortBy, $this->sortDirection)->paginate($this->perPage);

        return view('livewire.admin.subcategories.deleted-subcategories-datatable', compact('subcategories'));
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
    public function deleteConfirm($subcategory_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/productsPages.Are you sure, you want to permanently delete this subcategory ?'),
            confirmButtonText: __('admin/productsPages.Delete'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'forceDeleteSubcategory',
            id: $subcategory_id);
    }

    public function forceDeleteSubcategory($id)
    {
        try {
            $subcategory = Subcategory::withTrashed()->findOrFail($id);
            $subcategory->forceDelete();

            $this->dispatch('swalDone', text: __('admin/productsPages.Subcategory has been deleted permanently successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/productsPages.Subcategory hasn't been deleted permanently"),
                icon: 'error');
        }
    }
    ######## Force Delete #########

    ######## Restore #########
    public function restoreConfirm($subcategory_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/productsPages.Are you sure, you want to restore this subcategory ?'),
            confirmButtonText: __('admin/productsPages.Restore'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            icon: 'warning',
            method: 'restoreSubcategory',
            id: $subcategory_id);
    }

    public function restoreSubcategory($id)
    {
        try {
            $subcategory = Subcategory::withTrashed()->findOrFail($id);
            $subcategory->restore();

            $this->dispatch('swalDone', text: __('admin/productsPages.Subcategory has been restored successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/productsPages.Subcategory hasn't been restored"),
                icon: 'error');
        }
    }
    ######## Restore #########

    ######## Force Delete All #########
    public function forceDeleteAllConfirm()
    {
        $this->dispatch('swalConfirm', text: __('admin/productsPages.Are you sure, you want to delete all subcategories permanently ?'),
            confirmButtonText: __('admin/productsPages.Delete'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'forceDeleteAllSubcategories',
            id: '');
    }

    public function forceDeleteAllSubcategories()
    {
        try {
            Subcategory::onlyTrashed()->forceDelete();

            $this->dispatch('swalDone', text: __('admin/productsPages.All subcategories have been deleted successfully'),
                icon: 'info');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __('admin/productsPages.All subcategories haven\'t been deleted'),
                icon: 'error');
        }
    }
    ######## Force Delete All #########

    ######## Restore All #########

    public function restoreAllConfirm()
    {
        $this->dispatch('swalConfirm', text: __('admin/productsPages.Are you sure, you want to restore all subcategories ?'),
            confirmButtonText: __('admin/productsPages.Restore'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            icon: 'warning',
            method: 'restoreAllSubcategories',
            id: '');
    }

    public function restoreAllSubcategories()
    {
        try {
            Subcategory::onlyTrashed()->restore();

            $this->dispatch('swalDone', text: __('admin/productsPages.All subcategories have been restored successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __('admin/productsPages.All subcategories haven\'t been restored'),
                icon: 'error');
        }
    }
    ######## Restore All #########
}
