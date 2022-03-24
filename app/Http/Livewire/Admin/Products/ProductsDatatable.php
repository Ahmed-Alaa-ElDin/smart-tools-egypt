<?php

namespace App\Http\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class ProductsDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['softDeleteProduct'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $products = Product::with('subcategory','brand','thumbnail')
            // ->where('f_name->en', 'like', '%' . $this->search . '%')
            // ->orWhere('f_name->ar', 'like', '%' . $this->search . '%')
            // ->orWhere('l_name->en', 'like', '%' . $this->search . '%')
            // ->orWhere('l_name->ar', 'like', '%' . $this->search . '%')
            // ->orWhere('email', 'like', '%' . $this->search . '%')
            // ->orWhereHas('phones', function ($query) {
            //     $query->where('phone', 'like', '%' . $this->search . '%');
            // })
            // ->orWhereHas('roles', function ($query) {
            //     $query->where('name', 'like', '%' . $this->search . '%');
            // })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        // dd($products->first());

        return view('livewire.admin.products.products-datatable', compact('products'));
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
        if ($field == 'f_name') {
            return $this->sortBy = 'f_name->' . session('locale');
        }
        return $this->sortBy = $field;
    }
}
