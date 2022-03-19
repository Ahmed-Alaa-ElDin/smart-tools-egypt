<?php

namespace App\Http\Livewire\Admin\Countries;

use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class CitiesCountryDatatable extends Component
{

    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    public $country_id;

    protected $listeners = ['softDeleteCity'];

    public function mount()
    {

        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'cities.name->' . session('locale');
    }

    public function render()
    {
        $cities = City::with('governorate')->with('users')->with('deliveries')
            ->join('governorates', 'governorates.id', '=', 'governorate_id')
            ->join('countries', 'countries.id', '=', 'governorates.country_id')
            ->select('cities.*', 'governorates.name as governorate_name', 'countries.name->' . session('locale') . ' as country_name')
            ->withCount('users')
            ->withCount('deliveries')
            ->where('countries.id',$this->country_id)
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

        return view('livewire.admin.countries.cities-country-datatable', compact('cities'));
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
    public function deleteConfirm($city_id)
    {
        $this->dispatchBrowserEvent('swalConfirmSoftDelete', [
            "text" => __('admin/deliveriesPages.Are you sure, you want to delete this city ?'),
            'confirmButtonText' => __('admin/deliveriesPages.Delete'),
            'denyButtonText' => __('admin/deliveriesPages.Cancel'),
            'city_id' => $city_id,
        ]);
    }

    public function softDeleteCity($city_id)
    {
        try {
            $city = City::findOrFail($city_id);
            $city->delete();

            $this->dispatchBrowserEvent('swalCityDeleted', [
                "text" => __('admin/deliveriesPages.City has been deleted successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalCityDeleted', [
                "text" => __("admin/deliveriesPages.City hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Soft Delete #########
}
