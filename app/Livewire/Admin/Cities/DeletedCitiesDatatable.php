<?php

namespace App\Livewire\Admin\Cities;

use App\Models\City;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class DeletedCitiesDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['forceDeleteCity', 'restoreCity', 'forceDeleteAllCities', 'restoreAllCities'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'cities.name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $cities = City::onlyTrashed()->with('governorate','users','deliveries')
            ->join('governorates', 'governorates.id', '=', 'governorate_id')
            ->join('countries', 'countries.id', '=', 'governorates.country_id')
            ->select('cities.*', 'governorates.name as governorate_name', 'countries.name->' . session('locale') . ' as country_name')
            ->withCount('users')
            ->withCount('deliveries')
            ->where(function ($query) {
                return $query
                    ->where('cities.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('cities.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('governorates.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('governorates.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('countries.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('countries.name->en', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.cities.deleted-cities-datatable', compact('cities'));
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

    ######## Force Delete #########
    public function forceDeleteConfirm($city_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/deliveriesPages.Are you sure, you want to delete this city permanently ?'),
            confirmButtonText: __('admin/deliveriesPages.Delete'),
            denyButtonText: __('admin/deliveriesPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'forceDeleteCity',
            id: $city_id);
    }

    public function forceDeleteCity($id)
    {
        try {
            $city = City::onlyTrashed()->findOrFail($id);

            $city->forceDelete();

            $this->dispatch('swalDone', text: __('admin/deliveriesPages.City has been deleted permanently successfully'),
                icon: 'info');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/deliveriesPages.City has not been deleted permanently"),
                icon: 'error');
        }
    }
    ######## Force Delete #########

    ######## Restore #########
    public function restoreConfirm($city_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/deliveriesPages.Are you sure, you want to restore this city ?'),
            confirmButtonText: __('admin/deliveriesPages.Confirm'),
            denyButtonText: __('admin/deliveriesPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            icon: 'warning',
            method: 'restoreCity',
            id: $city_id);
    }

    public function restoreCity($id)
    {
        try {
            $city = City::onlyTrashed()->findOrFail($id);

            $city->restore();

            $this->dispatch('swalDone', text: __('admin/deliveriesPages.City has been restored successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/deliveriesPages.City has not been restored"),
                icon: 'error');
        }
    }
    ######## Restore #########


    ######## Force Delete All #########
    public function forceDeleteAllConfirm()
    {
        $this->dispatch('swalConfirm', text: __('admin/deliveriesPages.Are you sure, you want to delete all cities permanently ?'),
            confirmButtonText: __('admin/deliveriesPages.Delete'),
            denyButtonText: __('admin/deliveriesPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'forceDeleteAllCities',
            id: '');
    }

    public function forceDeleteAllCities()
    {
        try {
            City::onlyTrashed()->forceDelete();

            $this->dispatch('swalDone', text: __('admin/deliveriesPages.All cities have been deleted successfully'),
                icon: 'info');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __('admin/deliveriesPages.All cities haven\'t been deleted'),
                icon: 'error');
        }
    }
    ######## Force Delete All #########

    ######## Restore All #########

    public function restoreAllConfirm()
    {
        $this->dispatch('swalConfirm', text: __('admin/deliveriesPages.Are you sure, you want to restore all cities ?'),
            confirmButtonText: __('admin/deliveriesPages.Confirm'),
            denyButtonText: __('admin/deliveriesPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            icon: 'warning',
            method: 'restoreAllCities',
            id: '');
    }

    public function restoreAllCities()
    {
        try {
            $governorate = City::onlyTrashed()->restore();

            $this->dispatch('swalDone', text: __('admin/deliveriesPages.All cities have been restored successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __('admin/deliveriesPages.All cities haven\'t been restored'),
                icon: 'error');
        }
    }
    ######## Restore All #########
}
