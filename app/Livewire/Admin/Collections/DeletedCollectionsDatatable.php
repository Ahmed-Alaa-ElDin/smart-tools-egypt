<?php

namespace App\Livewire\Admin\Collections;

use App\Models\Collection;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class DeletedCollectionsDatatable extends Component
{
    use WithPagination;

    public $search = "";

    public $selectedCollections = [];

    public $subcategory_id = "%";
    public $brand_id = "%";
    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    protected $listeners = [
        'forceDeleteCollection',
        'forceDeleteAllCollection',
        'publishAllCollection',
        'hideAllCollection',
        'restoreCollection',
        'restoreAllCollection'
    ];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'collections.name->' . session('locale');

        $this->sortDirection = 'ASC';
    }

    // Render With each update
    public function render()
    {
        $collections = Collection::select([
            'collections.id',
            'collections.name',
            'slug',
            'original_price',
            'base_price',
            'final_price',
            'points',
            'publish',
            'under_reviewing',
        ])
            ->withCount('products')
            ->with([
                'thumbnail',
                'products' => fn ($q) => $q->select('products.id')
            ])
            ->where(function ($q) {
                $q
                    ->where('collections.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('collections.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('collections.base_price', 'like', '%' . $this->search . '%')
                    ->orWhere('collections.final_price', 'like', '%' . $this->search . '%');
            })
            ->onlyTrashed()

            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.collections.deleted-collections-datatable', compact('collections'));
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

    ######## Force Delete #########
    public function deleteConfirm($collection_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/productsPages.Are you sure, you want to permanently delete this collection ?'),
            confirmButtonText: __('admin/productsPages.Delete'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'forceDeleteCollection',
            id: $collection_id);
    }

    public function forceDeleteCollection($id)
    {
        try {
            $collection = Collection::withTrashed()->findOrFail($id);
            $collection->forceDelete();

            $this->dispatch('swalDone', text: __('admin/productsPages.Collection has been deleted permanently successfully'),
                icon: 'success');

            $this->selectedCollections = [];
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/productsPages.Collection has not been deleted permanently"),
                icon: 'error');
        }
    }
    ######## Force Delete #########

    ######## Restore #########
    public function restoreConfirm($collection_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/productsPages.Are you sure, you want to restore this collection ?'),
            confirmButtonText: __('admin/productsPages.Restore'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            icon: 'warning',
            method: 'restoreCollection',
            id: $collection_id);
    }

    public function restoreCollection($id)
    {
        try {
            $collection = Collection::withTrashed()->findOrFail($id);
            $collection->restore();

            $this->dispatch('swalDone', text: __('admin/productsPages.Collection has been restored successfully'),
                icon: 'success');

            $this->selectedCollections = [];
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/productsPages.Collection has not been restored"),
                icon: 'error');
        }
    }
    ######## Restore #########

    ######## Unselect All Collections #########
    public function unselectAll()
    {
        $this->selectedCollections = [];
    }
    ######## Unselect All Collections #########

    ######## Force Delete All Selected Collections #########
    public function deleteAllConfirm()
    {
        $this->dispatch('swalConfirm', text: __('admin/productsPages.Are you sure, you want to delete all selected collections permanently?'),
            confirmButtonText: __('admin/productsPages.Delete'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'forceDeleteAllCollection',
            id: '');
    }

    public function forceDeleteAllCollection()
    {
        try {
            foreach ($this->selectedCollections as $key => $selectedCollection) {

                $collection = Collection::withTrashed()->findOrFail($selectedCollection);
                $collection->forceDelete();
            }

            $this->dispatch('swalDone', text: __('admin/productsPages.Collections have been deleted permanently successfully'),
                icon: 'success');

            $this->selectedCollections = [];
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/productsPages.Collections haven't been deleted permanently"),
                icon: 'error');
        }
    }
    ######## Force Delete All Selected Collections #########

    ######## Restore All Selected Collections #########
    public function restoreAllConfirm()
    {
        $this->dispatch('swalConfirm', text: __('admin/productsPages.Are you sure, you want to restore all selected collections?'),
            confirmButtonText: __('admin/productsPages.Restore'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            icon: 'warning',
            method: 'restoreAllCollection',
            id: '');
    }

    public function restoreAllCollection()
    {
        try {
            foreach ($this->selectedCollections as $key => $selectedCollection) {

                $collection = Collection::withTrashed()->findOrFail($selectedCollection);
                $collection->restore();
            }

            $this->dispatch('swalDone', text: __('admin/productsPages.Collections have been restored successfully'),
                icon: 'success');

            $this->selectedCollections = [];
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/productsPages.Collections haven't been restored"),
                icon: 'error');
        }
    }
    ######## Restore All Selected Collections #########
}
