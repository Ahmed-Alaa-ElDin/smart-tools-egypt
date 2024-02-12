<?php

namespace App\Http\Livewire\Admin\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class DeletedCategoriesDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = [
        'forceDeleteCategory', 'restoreCategory', 'forceDeleteAllCategories', 'restoreAllCategories'
    ];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'categories.name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $categories = Category::onlyTrashed()
            ->select([
                'categories.id',
                'categories.name',
                'supercategories.name as supercategory_name',
                'supercategories.id as supercategory_id',
                'supercategory_id'
            ])
            ->join('supercategories', 'supercategory_id', '=', 'supercategories.id')
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
            ->orderBy($this->sortBy, $this->sortDirection)->paginate($this->perPage);


        // dd($categories);
        return view('livewire.admin.categories.deleted-categories-datatable', compact('categories'));
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

    ######## Force Delete #########
    public function deleteConfirm($category_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to permanently delete this category ?'),
            'confirmButtonText' => __('admin/productsPages.Delete'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'forceDeleteCategory',
            'id' => $category_id,
        ]);
    }

    public function forceDeleteCategory($category_id)
    {
        try {
            $category = Category::withTrashed()->findOrFail($category_id);
            $category->forceDelete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Category has been deleted permanently successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Category hasn't been deleted permanently"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Force Delete #########

    ######## Restore #########
    public function restoreConfirm($category_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to restore this category ?'),
            'confirmButtonText' => __('admin/productsPages.Restore'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'red',
            'confirmButtonColor' => 'green',
            'focusDeny' => false,
            'icon' => 'warning',
            'method' => 'restoreCategory',
            'id' => $category_id,
        ]);
    }

    public function restoreCategory($category_id)
    {
        try {
            $category = Category::withTrashed()->findOrFail($category_id);
            $category->restore();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Category has been restored successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Category hasn't been restored"),
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
            'method' => 'forceDeleteAllCategories',
            'id' => ''
        ]);
    }

    public function forceDeleteAllCategories()
    {
        try {
            Category::onlyTrashed()->forceDelete();

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
            'method' => 'restoreAllCategories',
            'id' => '',
        ]);
    }

    public function restoreAllCategories()
    {
        try {
            Category::onlyTrashed()->restore();

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
