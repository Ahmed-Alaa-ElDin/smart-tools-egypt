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

        $this->sortBy = 'name->' . session('locale');
    }

    public function render()
    {
        $governorates = Governorate::with('country')->with('deliveries')->with('users')->with('cities')
            ->where('country_id', $this->country_id)
            ->where(function ($query) {
                return $query
                    ->where('name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('name->ar', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);;

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

    ######## Soft Delete #########
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
    ######## Soft Delete #########

}
