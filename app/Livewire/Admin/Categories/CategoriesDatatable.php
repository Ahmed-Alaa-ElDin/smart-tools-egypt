<?php

namespace App\Livewire\Admin\Categories;

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
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'categories.name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $categories = Category::select([
            'categories.id',
            'categories.name',
            'categories.publish',
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

        return view('livewire.admin.categories.categories-datatable', compact('categories'));
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
            return $this->sortBy = 'categories.name->' . session('locale');
        }

        return $this->sortBy = $field;
    }

    ######################## Publish Toggle :: Start ############################
    public function publish($category_id)
    {
        $category_id = Category::findOrFail($category_id);

        try {
            $category_id->update([
                'publish' => $category_id->publish ? 0 : 1
            ]);

            $this->dispatch('swalDone', text: $category_id->publish ? __('admin/productsPages.Category has been published') : __('admin/productsPages.Category has been hidden'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: $category_id->publish ? __("admin/productsPages.Category has not been published") : __("admin/productsPages.Category has not been hidden"),
                icon: 'error');
        }
    }
    ######################## Publish Toggle :: End ############################

    ######## Deleted #########
    public function deleteConfirm($category_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/productsPages.Are you sure, you want to delete this category ?'),
            confirmButtonText: __('admin/productsPages.Delete'),
            denyButtonText: __('admin/productsPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'softDeleteCategory',
            id: $category_id);
    }

    public function softDeleteCategory($id)
    {
        try {
            $product = Category::findOrFail($id);
            $product->delete();

            $this->dispatch('swalDone', text: __('admin/productsPages.Category has been deleted successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/productsPages.Category has not been deleted"),
                icon: 'error');
        }
    }
    ######## Deleted #########
}
