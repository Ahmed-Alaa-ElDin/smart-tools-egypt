<?php

namespace App\Http\Livewire\Admin\Countries;

use App\Models\Governorate;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class GovernoratesCountryDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    public $country_id;

    protected $listeners = ['softDeleteGovernorate'];

    public function mount()
    {

        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'governorates.name->' . session('locale');
    }

    public function render()
    {
        $governorates = Governorate::with('country','deliveries','users','cities')
            ->join('countries', 'countries.id', '=', 'governorates.country_id')
            ->select('governorates.*', 'countries.name as country_name')
            ->withCount('users')
            ->withCount('deliveries')
            ->withCount('cities')
            ->where('country_id',$this->country_id)
            ->where(function ($query) {
                return $query
                    ->where('governorates.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('governorates.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('countries.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('countries.name->ar', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.countries.governorates-country-datatable', compact('governorates'));
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

    ######## Deleted #########
    public function deleteConfirm($governorate_id)
    {
        $this->dispatchBrowserEvent('swalConfirmSoftDelete', [
            "text" => __('admin/deliveriesPages.Are you sure, you want to delete this governorate ?'),
            'confirmButtonText' => __('admin/deliveriesPages.Delete'),
            'denyButtonText' => __('admin/deliveriesPages.Cancel'),
            'governorate_id' => $governorate_id,
        ]);
    }

    public function softDeleteGovernorate($governorate_id)
    {
        try {
            $governorate = Governorate::findOrFail($governorate_id);
            $governorate->delete();

            $this->dispatchBrowserEvent('swalGovernorateDeleted', [
                "text" => __('admin/deliveriesPages.Governorate has been deleted successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalGovernorateDeleted', [
                "text" => __("admin/deliveriesPages.Governorate hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Deleted #########

}
