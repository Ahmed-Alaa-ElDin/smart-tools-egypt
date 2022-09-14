<?php

namespace App\Http\Livewire\Admin\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriesDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    public $supercategory_id = '%';

    protected $listeners = ['softDeleteCategory'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'categories.name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $categories = Category::select([
            'categories.id',
            'categories.name',
            'supercategories.name as supercategory_name',
            'supercategories.id as supercategory_id',
            'supercategory_id'
        ])
            ->leftJoin('supercategories', 'supercategory_id', '=', 'supercategories.id')
            ->with(['supercategory' => function ($q) {
                return $q->select('id', 'name');
            }])
            ->withCount('subcategories')
            ->where(function ($q) {
                return $q
                    ->where('supercategories.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('supercategories.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('supercategories.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('supercategories.name->en', 'like', '%' . $this->search . '%');
            })
            ->where('supercategory_id', 'like', $this->supercategory_id)
            ->orderBy($this->sortBy, $this->sortDirection)->paginate($this->perPage);


        // dd($categories);
        return view('livewire.admin.categories.categories-datatable', compact('categories'));
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
            return $this->sortBy = 'categories.name->' . session('locale');
        }

        return $this->sortBy = $field;
    }

    ######## Deleted #########
    public function deleteConfirm($category_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to delete this category ?'),
            'confirmButtonText' => __('admin/productsPages.Delete'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'softDeleteCategory',
            'id' => $category_id,
        ]);
    }

    public function softDeleteCategory($category_id)
    {
        try {
            $product = Category::findOrFail($category_id);
            $product->delete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Category has been deleted successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Category hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Deleted #########
}
