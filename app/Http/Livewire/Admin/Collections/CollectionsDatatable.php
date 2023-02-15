<?php

namespace App\Http\Livewire\Admin\Collections;

use App\Models\Collection;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class CollectionsDatatable extends Component
{
    use WithPagination;

    public $search = "";

    public $selectedCollections = [];

    public $subcategory_id = "%";
    public $brand_id = "%";

    protected $listeners = ['softDeleteCollection', 'softDeleteAllCollection', 'publishAllCollection', 'hideAllCollection'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');

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

    public function publish($collection_id)
    {
        $collection = Collection::findOrFail($collection_id);

        try {
            $collection->update([
                'publish' => $collection->publish ? 0 : 1
            ]);

            $this->dispatchBrowserEvent('swalDone', [
                "text" => $collection->publish ? __('admin/productsPages.Collection has been published') : __('admin/productsPages.Collection has been hidden'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => $collection->publish ? __("admin/productsPages.Collection hasn't been published") : __("admin/productsPages.Collection hasn't been hidden"),
                'icon' => 'error'
            ]);
        }
    }

    ######## Deleted #########
    public function deleteConfirm($collection_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to delete this collection ?'),
            'confirmButtonText' => __('admin/productsPages.Delete'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'softDeleteCollection',
            'id' => $collection_id,
        ]);
    }

    public function softDeleteCollection($collection_id)
    {
        try {
            $collection = Collection::findOrFail($collection_id);
            $collection->delete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Collection has been deleted successfully'),
                'icon' => 'success'
            ]);

            $this->selectedCollections = [];
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Collection hasn't been deleted"),
                'icon' => 'error'
            ]);
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
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to delete all selected collections ?'),
            'confirmButtonText' => __('admin/productsPages.Delete'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'softDeleteAllCollection',
            'id' => '',
        ]);
    }

    public function softDeleteAllCollection()
    {
        try {
            foreach ($this->selectedCollections as $key => $selectedCollection) {

                $collection = Collection::findOrFail($selectedCollection);
                $collection->delete();
            }

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Collections have been deleted successfully'),
                'icon' => 'success'
            ]);

            $this->selectedCollections = [];
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Collections haven't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Deleted All Selected Collections #########


    ######## Publish All Selected Collections #########
    public function publishAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to publish all selected collections ?'),
            'confirmButtonText' => __('admin/productsPages.Publish'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'red',
            'confirmButtonColor' => 'green',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'publishAllCollection',
            'id' => '',
        ]);
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

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Collections have been published'),
                'icon' => 'success'
            ]);

            $this->selectedCollections = [];
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Collections haven't been published"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Publish All Selected Collections #########

    ######## Hide All Selected Collections #########
    public function hideAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to hide all selected collections ?'),
            'confirmButtonText' => __('admin/productsPages.Hide'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => false,
            'icon' => 'warning',
            'method' => 'hideAllCollection',
            'id' => '',
        ]);
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

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Collections have been hidden'),
                'icon' => 'success'
            ]);

            $this->selectedCollections = [];
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Collections haven't been hidden"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Hide All Selected Collections #########
}
