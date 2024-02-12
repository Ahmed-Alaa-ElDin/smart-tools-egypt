<?php

namespace App\Http\Livewire\Admin\Governorates;

use App\Models\Country;
use App\Models\Governorate;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class GovernoratesDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $country_id = null;

    public $search = "";

    protected $listeners = ['softDeleteGovernorate'];

    public function mount()
    {

        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'governorates.name->' . session('locale');
    }

    public function render()
    {
        $governorates = Governorate::with(['country' => function ($query) {
            $query->withTrashed();
        }, 'deliveries', 'customers', 'cities'])
            ->join('countries', 'countries.id', '=', 'governorates.country_id')
            ->select('governorates.*', 'countries.name as country_name')
            ->withCount('customers')
            ->withCount('deliveries')
            ->withCount('cities')
            ->where(function ($query) {
                return $query
                    ->where('governorates.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('governorates.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('countries.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('countries.name->ar', 'like', '%' . $this->search . '%');
            })
            ->where(function ($query) {
                if ($this->country_id) {
                    return $query->whereHas('country', fn ($q) => $q->where('countries.id', $this->country_id));
                }
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.governorates.governorates-datatable', compact('governorates'));
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
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/deliveriesPages.Are you sure, you want to delete this governorate ?'),
            'confirmButtonText' => __('admin/deliveriesPages.Delete'),
            'denyButtonText' => __('admin/deliveriesPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'softDeleteGovernorate',
            'id' => $governorate_id,
        ]);
    }

    public function softDeleteGovernorate($governorate_id)
    {
        try {
            $governorate = Governorate::findOrFail($governorate_id);
            $governorate->delete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/deliveriesPages.Governorate has been deleted successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/deliveriesPages.Governorate hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Deleted #########


}
