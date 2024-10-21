<?php

namespace App\Livewire\Admin\Supercategories;

use App\Models\Supercategory;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class SupercategoriesDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['softDeleteSupercategory'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $supercategories = Supercategory::select([
            'id',
            'name',
            'publish',
            'icon',
        ])
            ->withCount('categories')
            ->withCount('subcategories')
            ->where(function ($q) {
                return $q
                    ->where('name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('name->en', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)->paginate($this->perPage);

        return view('livewire.admin.supercategories.supercategories-datatable', compact('supercategories'));
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

    ######################## Publish Toggle :: Start ############################
    public function publish($supercategory_id)
    {
        $supercategory_id = Supercategory::findOrFail($supercategory_id);

        try {
            $supercategory_id->update([
                'publish' => $supercategory_id->publish ? 0 : 1
            ]);

            $this->dispatch('swalDone', text: $supercategory_id->publish ? __('admin/productsPages.Supercategory has been published') : __('admin/productsPages.Supercategory has been hidden'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: $supercategory_id->publish ? __("admin/productsPages.Supercategory has not been published") : __("admin/productsPages.Supercategory has not been hidden"),
                icon: 'error');
        }
    }
    ######################## Publish Toggle :: End ############################

    ######## Deleted #########
    public function deleteConfirm($supercategories_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/productsPages.Are you sure, you want to delete this supercategory ?'),
            confirmButtonText: __('admin/productsPages.Delete'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'softDeleteSupercategory',
            id: $supercategories_id);
    }

    public function softDeleteSupercategory($id)
    {
        try {
            $supercategory = Supercategory::findOrFail($id);
            $supercategory->delete();

            $this->dispatch('swalDone', text: __('admin/productsPages.Supercategory has been deleted successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/productsPages.Supercategory has not been deleted"),
                icon: 'error');
        }
    }
    ######## Deleted #########
}
