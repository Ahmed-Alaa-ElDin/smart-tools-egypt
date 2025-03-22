<?php

namespace App\Livewire\Admin\Cities;

use App\Models\City;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class CitiesDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    public $country_id;
    public $governorate_id;

    protected $listeners = ['softDeleteCity'];

    public function mount()
    {

        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'cities.name->' . session('locale');
    }

    public function render()
    {
        $cities = City::with('governorate:id,name', 'users:id', 'deliveries:id')
            ->join('governorates', 'governorates.id', '=', 'governorate_id')
            ->join('countries', 'countries.id', '=', 'governorates.country_id')
            ->select('cities.*', 'governorates.name as governorate_name', 'countries.name->' . session('locale') . ' as country_name')
            ->withCount([
                'users' => fn ($q) => $q->whereHas('roles', fn ($q) => $q->where('name', 'Customer')),
                'deliveries'
            ])
            ->where(function ($query) {
                return $query
                    ->where('cities.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('cities.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('governorates.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('governorates.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('countries.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('countries.name->en', 'like', '%' . $this->search . '%');
            })
            ->where(function ($q) {
                if ($this->governorate_id) {
                    return $q->where('governorates.id', $this->governorate_id);
                }
                if ($this->country_id) {
                    return $q->where('countries.id', $this->country_id);
                }
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.cities.cities-datatable', compact(
            'cities'
        ));
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
            return $this->sortBy = 'name->' . session('locale');
        }
        return $this->sortBy = $field;
    }

    ######## Deleted #########
    public function deleteConfirm($city_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/deliveriesPages.Are you sure, you want to delete this city ?'),
            confirmButtonText: __('admin/deliveriesPages.Delete'),
            denyButtonText: __('admin/deliveriesPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'softDeleteCity',
            id: $city_id);
    }

    public function softDeleteCity($id)
    {
        try {
            $city = City::findOrFail($id);
            $city->delete();

            $this->dispatch('swalDone', text: __('admin/deliveriesPages.City has been deleted successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/deliveriesPages.City has not been deleted"),
                icon: 'error');
        }
    }
    ######## Deleted #########
}
