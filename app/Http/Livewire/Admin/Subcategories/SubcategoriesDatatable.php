<?php

namespace App\Http\Livewire\Admin\Subcategories;

use App\Models\Subcategory;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class SubcategoriesDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    public $category_id = '%';
    public $supercategory_id = '%';

    protected $listeners = ['softDeleteSubcategory'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'subcategories.name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $subcategories = Subcategory::select([
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
            ->where('category_id', 'like', $this->category_id)
            ->where('supercategory_id', 'like', $this->supercategory_id)
            ->leftJoin('categories', 'category_id', '=', 'categories.id')
            ->leftJoin('supercategories', 'categories.supercategory_id', '=', 'supercategories.id')
            ->orderBy($this->sortBy, $this->sortDirection)->paginate($this->perPage);

        // dd($subcategories);
        return view('livewire.admin.subcategories.subcategories-datatable', compact('subcategories'));
    }

    // reset pagination after new search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    ######################## Publish Toggle :: Start ############################
    public function publish($subcategory_id)
    {
        $subcategory_id = Subcategory::findOrFail($subcategory_id);

        try {
            $subcategory_id->update([
                'publish' => $subcategory_id->publish ? 0 : 1
            ]);

            $this->dispatchBrowserEvent('swalDone', [
                "text" => $subcategory_id->publish ? __('admin/productsPages.Subcategory has been published') : __('admin/productsPages.Subcategory has been hidden'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => $subcategory_id->publish ? __("admin/productsPages.Subcategory hasn't been published") : __("admin/productsPages.Subcategory hasn't been hidden"),
                'icon' => 'error'
            ]);
        }
    }
    ######################## Publish Toggle :: End ############################

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

    ######## Deleted #########
    public function deleteConfirm($subcategories_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to delete this subcategory ?'),
            'confirmButtonText' => __('admin/productsPages.Delete'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'softDeleteSubcategory',
            'id' => $subcategories_id,
        ]);
    }

    public function softDeleteSubcategory($subcategories_id)
    {
        try {
            $subcategory = Subcategory::findOrFail($subcategories_id);
            $subcategory->delete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Subcategory has been deleted successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Subcategory hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Deleted #########
}
