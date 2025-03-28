<?php

namespace App\Livewire\Admin\Countries;

use App\Models\Country;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class CountriesDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['softDeleteCountry'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $countries = Country::with('deliveries', 'governorates', 'customers', 'cities')
            ->withCount('customers')
            ->withCount('deliveries')
            ->withCount('cities')
            ->withCount('governorates')
            ->where(function ($query) {
                return $query
                    ->where('name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('name->ar', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.countries.countries-datatable', compact('countries'));
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
    public function deleteConfirm($country_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/deliveriesPages.Are you sure, you want to delete this country ?'),
            confirmButtonText: __('admin/deliveriesPages.Delete'),
            denyButtonText: __('admin/deliveriesPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            method: 'softDeleteCountry',
            id: $country_id);
    }

    public function softDeleteCountry($id)
    {
        try {
            $user = Country::findOrFail($id);
            $user->delete();

            $this->dispatch('swalDone', text: __('admin/deliveriesPages.Country has been deleted successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/deliveriesPages.Country has not been deleted"),
                icon: 'error');
        }
    }
    ######## Deleted #########

}
