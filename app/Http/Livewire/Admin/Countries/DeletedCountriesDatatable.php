<?php

namespace App\Http\Livewire\Admin\Countries;

use App\Models\Country;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class DeletedCountriesDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['forceDeleteCountry', 'restoreCountry', 'forceDeleteAllCountries', 'restoreAllCountries'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $countries = Country::onlyTrashed()->with('deliveries','governorates','users','cities')
            ->withCount('deliveries')
            ->withCount('governorates')
            ->withCount('users')
            ->withCount('cities')

            ->where(function ($query) {
                return $query
                    ->where('name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('name->ar', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.countries.deleted-countries-datatable', compact('countries'));
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
    public function forceDeleteConfirm($country_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/deliveriesPages.Are you sure, you want to delete this country permanently ?'),
            'confirmButtonText' => __('admin/deliveriesPages.Delete'),
            'denyButtonText' => __('admin/deliveriesPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'forceDeleteCountry',
            'id' => $country_id,
        ]);
    }

    public function forceDeleteCountry($country_id)
    {
        try {
            $country = Country::onlyTrashed()->findOrFail($country_id);

            $country->forceDelete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/deliveriesPages.Country has been deleted permanently successfully'),
                'icon' => 'info'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/deliveriesPages.Country hasn't been deleted permanently"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Force Delete #########

    ######## Restore #########
    public function restoreConfirm($country_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/deliveriesPages.Are you sure, you want to restore this country ?'),
            'confirmButtonText' => __('admin/deliveriesPages.Confirm'),
            'denyButtonText' => __('admin/deliveriesPages.Cancel'),
            'denyButtonColor' => 'red',
            'confirmButtonColor' => 'green',
            'focusDeny' => false,
            'icon' => 'warning',
            'method' => 'restoreCountry',
            'id' => $country_id,
        ]);
    }

    public function restoreCountry($country_id)
    {
        try {
            $country = Country::onlyTrashed()->findOrFail($country_id);

            $country->restore();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/deliveriesPages.Country has been restored successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/deliveriesPages.Country hasn't been restored"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Restore #########


    ######## Force Delete All #########
    public function forceDeleteAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/deliveriesPages.Are you sure, you want to delete all countries permanently ?'),
            'confirmButtonText' => __('admin/deliveriesPages.Delete'),
            'denyButtonText' => __('admin/deliveriesPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'forceDeleteAllCountries',
            'id' => ''
        ]);
    }

    public function forceDeleteAllCountries()
    {
        try {
            Country::onlyTrashed()->forceDelete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/deliveriesPages.All countries have been deleted successfully'),
                'icon' => 'info'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/deliveriesPages.All countries haven\'t been deleted'),
                'icon' => 'error'
            ]);
        }
    }
    ######## Force Delete All #########

    ######## Restore All #########

    public function restoreAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/deliveriesPages.Are you sure, you want to restore all countries ?'),
            'confirmButtonText' => __('admin/deliveriesPages.Confirm'),
            'denyButtonText' => __('admin/deliveriesPages.Cancel'),
            'denyButtonColor' => 'red',
            'confirmButtonColor' => 'green',
            'focusDeny' => false,
            'icon' => 'warning',
            'method' => 'restoreAllCountries',
            'id' => '',
        ]);
    }

    public function restoreAllCountries()
    {
        try {
            $country = Country::onlyTrashed()->restore();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/deliveriesPages.All countries have been restored successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/deliveriesPages.All countries haven\'t been restored'),
                'icon' => 'error'
            ]);
        }
    }
    ######## Restore All #########

}
