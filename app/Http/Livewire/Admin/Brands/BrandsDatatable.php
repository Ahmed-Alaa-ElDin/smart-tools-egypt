<?php

namespace App\Http\Livewire\Admin\Brands;

use App\Models\Brand;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class BrandsDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['softDeleteBrand'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'name';
    }

    // Render With each update
    public function render()
    {
        $brands = Brand::select([
            'id',
            'name',
            'logo_path',
            'country_id',
        ])->with('country')
            ->withCount('products')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhereHas('country', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)->paginate($this->perPage);

        return view('livewire.admin.brands.brands-datatable', compact('brands'));
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

    ######## Deleted #########
    public function deleteConfirm($brand_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/productsPages.Are you sure, you want to delete this brand ?'),
            'confirmButtonText' => __('admin/productsPages.Delete'),
            'denyButtonText' => __('admin/productsPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'softDeleteBrand',
            'id' => $brand_id,
        ]);
    }

    public function softDeleteBrand($brand_id)
    {
        try {
            $product = Brand::findOrFail($brand_id);
            $product->delete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/productsPages.Brand has been deleted successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/productsPages.Brand hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Deleted #########

}
