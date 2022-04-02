<?php

namespace App\Http\Livewire\Admin\Supercategories;

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
        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $supercategories = Supercategory::select([
            'id',
            'name',
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

    ######## Soft Delete #########
    public function deleteConfirm($supercategories_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to delete this supercategory ?'),
            'confirmButtonText' => __('admin/productsPages.Delete'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'confirmButtonColor' => 'red',
            'func' => 'softDeleteSupercategory',
            'supercategory_id' => $supercategories_id,
        ]);
    }

    public function softDeleteSupercategory($supercategories_id)
    {
        try {
            $supercategory = Supercategory::findOrFail($supercategories_id);
            $supercategory->delete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Supercategory has been deleted successfully'),
                'icon' => 'success'
            ]);

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Supercategory hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Soft Delete #########
}
