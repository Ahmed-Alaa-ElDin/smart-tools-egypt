<?php

namespace App\Http\Livewire\Admin\Supercategories;

use App\Models\Supercategory;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class DeletedSupercategoriesDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = [
        'forceDeleteSupercategory', 'restoreSupercategory', 'forceDeleteAllSupercategories', 'restoreAllSupercategories'
    ];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'name';
    }

    // Render With each update
    public function render()
    {
        $supercategories = Supercategory::onlyTrashed()
            ->select([
                'id',
                'name',
                'icon',
            ])
            ->withCount('categories')
            ->withCount('subcategories')
            ->where(function ($q) {
                return $q
                    ->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)->paginate($this->perPage);

        return view('livewire.admin.supercategories.deleted-supercategories-datatable', compact('supercategories'));
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
    public function deleteConfirm($supercategory_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to permanently delete this supercategory ?'),
            'confirmButtonText' => __('admin/productsPages.Delete'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'forceDeleteSupercategory',
            'id' => $supercategory_id,
        ]);
    }

    public function forceDeleteSupercategory($supercategory_id)
    {
        try {
            $supercategory = Supercategory::withTrashed()->findOrFail($supercategory_id);
            $supercategory->forceDelete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Supercategory has been deleted permanently successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Supercategory hasn't been deleted permanently"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Force Delete #########

    ######## Restore #########
    public function restoreConfirm($supercategory_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to restore this supercategory ?'),
            'confirmButtonText' => __('admin/productsPages.Restore'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'red',
            'confirmButtonColor' => 'green',
            'focusDeny' => false,
            'icon' => 'warning',
            'method' => 'restoreSupercategory',
            'id' => $supercategory_id,
        ]);
    }

    public function restoreSupercategory($supercategory_id)
    {
        try {
            $supercategory = Supercategory::withTrashed()->findOrFail($supercategory_id);
            $supercategory->restore();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Supercategory has been restored successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Supercategory hasn't been restored"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Restore #########

    ######## Force Delete All #########
    public function forceDeleteAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to delete all supercategories permanently ?'),
            'confirmButtonText' => __('admin/productsPages.Delete'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'forceDeleteAllSupercategories',
            'id' => ''
        ]);
    }

    public function forceDeleteAllSupercategories()
    {
        try {
            Supercategory::onlyTrashed()->forceDelete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.All supercategories have been deleted successfully'),
                'icon' => 'info'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.All supercategories haven\'t been deleted'),
                'icon' => 'error'
            ]);
        }
    }
    ######## Force Delete All #########

    ######## Restore All #########

    public function restoreAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to restore all supercategories ?'),
            'confirmButtonText' => __('admin/productsPages.Restore'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'red',
            'confirmButtonColor' => 'green',
            'focusDeny' => false,
            'icon' => 'warning',
            'method' => 'restoreAllSupercategories',
            'id' => '',
        ]);
    }

    public function restoreAllSupercategories()
    {
        try {
            Supercategory::onlyTrashed()->restore();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.All supercategories have been restored successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.All supercategories haven\'t been restored'),
                'icon' => 'error'
            ]);
        }
    }
    ######## Restore All #########
}
