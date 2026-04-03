<?php

namespace App\Livewire\Admin\Collections;

use App\Models\Collection;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\Front\meta\MetaCatalogService;

class CollectionsDatatable extends Component
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
        'softDeleteCollection', 
        'softDeleteAllCollection', 
        'publishAllCollection', 
        'hideAllCollection',
        'syncSelectedToMeta',
        'removeSelectedFromMeta'
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

            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.collections.collections-datatable', compact('collections'));
    }

    // reset pagination after new search
    public function updatingSearch()
    {
        $this->resetPage();
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

    public function publish($collection_id)
    {
        $collection = Collection::findOrFail($collection_id);

        try {
            $collection->update([
                'publish' => $collection->publish ? 0 : 1
            ]);

            $this->dispatch(
                'swalDone',
                text: $collection->publish ? __('admin/productsPages.Collection has been published') : __('admin/productsPages.Collection has been hidden'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: $collection->publish ? __("admin/productsPages.Collection has not been published") : __("admin/productsPages.Collection has not been hidden"),
                icon: 'error'
            );
        }
    }

    ######## Deleted #########
    public function deleteConfirm($collection_id)
    {
        $this->dispatch(
            'swalConfirm',
            text: __('admin/productsPages.Are you sure, you want to delete this collection ?'),
            confirmButtonText: __('admin/productsPages.Delete'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'softDeleteCollection',
            id: $collection_id
        );
    }

    public function softDeleteCollection($id)
    {
        try {
            $collection = Collection::findOrFail($id);
            $collection->delete();

            $this->dispatch(
                'swalDone',
                text: __('admin/productsPages.Collection has been deleted successfully'),
                icon: 'success'
            );

            $this->selectedCollections = [];
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/productsPages.Collection has not been deleted"),
                icon: 'error'
            );
        }
    }
    ######## Deleted #########


    ######## Unselect All Collections #########
    public function unselectAll()
    {
        $this->selectedCollections = [];
    }
    ######## Unselect All Collections #########

    ######## Deleted All Selected Collections #########
    public function deleteAllConfirm()
    {
        $this->dispatch(
            'swalConfirm',
            text: __('admin/productsPages.Are you sure, you want to delete all selected collections ?'),
            confirmButtonText: __('admin/productsPages.Delete'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'softDeleteAllCollection',
            id: ''
        );
    }

    public function softDeleteAllCollection()
    {
        try {
            foreach ($this->selectedCollections as $key => $selectedCollection) {

                $collection = Collection::findOrFail($selectedCollection);
                $collection->delete();
            }

            $this->dispatch(
                'swalDone',
                text: __('admin/productsPages.Collections have been deleted successfully'),
                icon: 'success'
            );

            $this->selectedCollections = [];
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/productsPages.Collections haven't been deleted"),
                icon: 'error'
            );
        }
    }
    ######## Deleted All Selected Collections #########


    ######## Publish All Selected Collections #########
    public function publishAllConfirm()
    {
        $this->dispatch(
            'swalConfirm',
            text: __('admin/productsPages.Are you sure, you want to publish all selected collections ?'),
            confirmButtonText: __('admin/productsPages.Publish'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: true,
            icon: 'warning',
            method: 'publishAllCollection',
            id: ''
        );
    }

    public function publishAllCollection()
    {

        try {
            foreach ($this->selectedCollections as $key => $selectedCollection) {
                $collection = Collection::findOrFail($selectedCollection);

                $collection->update([
                    'publish' => 1
                ]);
            }

            $this->dispatch(
                'swalDone',
                text: __('admin/productsPages.Collections have been published'),
                icon: 'success'
            );

            $this->selectedCollections = [];
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/productsPages.Collections haven't been published"),
                icon: 'error'
            );
        }
    }
    ######## Publish All Selected Collections #########

    ######## Hide All Selected Collections #########
    public function hideAllConfirm()
    {
        $this->dispatch(
            'swalConfirm',
            text: __('admin/productsPages.Are you sure, you want to hide all selected collections ?'),
            confirmButtonText: __('admin/productsPages.Hide'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: false,
            icon: 'warning',
            method: 'hideAllCollection',
            id: ''
        );
    }

    public function hideAllCollection()
    {

        try {
            foreach ($this->selectedCollections as $key => $selectedCollection) {
                $collection = Collection::findOrFail($selectedCollection);

                $collection->update([
                    'publish' => 0
                ]);
            }

            $this->dispatch(
                'swalDone',
                text: __('admin/productsPages.Collections have been hidden'),
                icon: 'success'
            );

            $this->selectedCollections = [];
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/productsPages.Collections haven't been hidden"),
                icon: 'error'
            );
        }
    }
    ######## Hide All Selected Collections #########
    
    ######## Meta Catalog Sync #########
    public function syncToMeta($id)
    {
        $collection = Collection::findOrFail($id);
        $service = new MetaCatalogService();

        if ($service->syncCollection($collection)) {
            $this->dispatch('swalDone', text: __('admin/productsPages.Sync Successful'), icon: 'success');
        } else {
            $this->dispatch('swalDone', text: __('admin/productsPages.Sync Failed'), icon: 'error');
        }
    }

    public function removeFromMeta($id)
    {
        $service = new MetaCatalogService();

        if ($service->deleteItem($id, true)) {
            $this->dispatch('swalDone', text: __('admin/productsPages.Removed from Meta'), icon: 'success');
        } else {
            $this->dispatch('swalDone', text: __('admin/productsPages.Removal Failed'), icon: 'error');
        }
    }

    public function syncSelectedToMetaConfirm()
    {
        $this->dispatch('swalConfirm', 
            text: __('admin/productsPages.Sync all selected collections to Facebook Catalog?'),
            confirmButtonText: __('admin/productsPages.Sync'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: true,
            icon: 'warning',
            method: 'syncSelectedToMeta',
            id: '');
    }

    public function syncSelectedToMeta()
    {
        $collections = Collection::whereIn('id', $this->selectedCollections)->get();
        $service = new MetaCatalogService();

        if ($service->syncItems($collections)) {
            $this->dispatch('swalDone', text: __('admin/productsPages.Bulk Sync Successful'), icon: 'success');
            $this->selectedCollections = [];
        } else {
            $this->dispatch('swalDone', text: __('admin/productsPages.Bulk Sync Failed'), icon: 'error');
        }
    }

    public function removeSelectedFromMetaConfirm()
    {
        $this->dispatch('swalConfirm', 
            text: __('admin/productsPages.Remove all selected collections from Facebook Catalog?'),
            confirmButtonText: __('admin/productsPages.Remove'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'removeSelectedFromMeta',
            id: ''
        );
    }

    public function removeSelectedFromMeta()
    {
        $service = new MetaCatalogService();
        $success = true;

        foreach ($this->selectedCollections as $id) {
            if (!$service->deleteItem($id, true)) {
                $success = false;
            }
        }

        if ($success) {
            $this->dispatch('swalDone', text: __('admin/productsPages.Bulk Removal Successful'), icon: 'success');
            $this->selectedCollections = [];
        } else {
            $this->dispatch('swalDone', text: __('admin/productsPages.Some removals failed'), icon: 'warning');
        }
    }
    ######## Meta Catalog Sync #########
}
