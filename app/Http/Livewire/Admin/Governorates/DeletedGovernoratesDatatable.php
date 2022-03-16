<?php

namespace App\Http\Livewire\Admin\Governorates;

use App\Models\Governorate;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class DeletedGovernoratesDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['forceDeleteGovernorate', 'restoreGovernorate', 'forceDeleteAllGovernorates', 'restoreAllGovernorates'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'governorates.name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $governorates = Governorate::onlyTrashed()->with('country')->with('deliveries')->with('users')->with('cities')
            ->join('countries','countries.id','=','governorates.country_id')
            ->select('governorates.*','countries.name as country_name')
            ->where(function ($query) {
                return $query
                    ->where('governorates.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('governorates.name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('countries.name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('countries.name->ar', 'like', '%' . $this->search . '%');

            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.governorates.deleted-governorates-datatable', compact('governorates'));
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
    public function forceDeleteConfirm($governorate_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/deliveriesPages.Are you sure, you want to delete this country permanently ?'),
            'confirmButtonText' => __('admin/deliveriesPages.Delete'),
            'denyButtonText' => __('admin/deliveriesPages.Cancel'),
            'denyButtonColor' => 'gray',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'forceDeleteGovernorate',
            'governorate_id' => $governorate_id,
        ]);
    }

    public function forceDeleteGovernorate($governorate_id)
    {
        try {
            $country = Governorate::onlyTrashed()->findOrFail($governorate_id);

            $country->forceDelete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/deliveriesPages.Governorate has been deleted permanently successfully'),
                'icon' => 'info'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/deliveriesPages.Governorate hasn't been deleted permanently"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Force Delete #########

    ######## Restore #########
    public function restoreConfirm($governorate_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/deliveriesPages.Are you sure, you want to restore this governorate ?'),
            'confirmButtonText' => __('admin/deliveriesPages.Confirm'),
            'denyButtonText' => __('admin/deliveriesPages.Cancel'),
            'denyButtonColor' => 'gray',
            'confirmButtonColor' => 'green',
            'focusDeny' => false,
            'icon' => 'warning',
            'method' => 'restoreGovernorate',
            'governorate_id' => $governorate_id,
        ]);
    }

    public function restoreGovernorate($governorate_id)
    {
        try {
            $governorate = Governorate::onlyTrashed()->findOrFail($governorate_id);

            $governorate->restore();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/deliveriesPages.Governorate has been restored successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/deliveriesPages.Governorate hasn't been restored"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Restore #########


    ######## Force Delete All #########
    public function forceDeleteAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/deliveriesPages.Are you sure, you want to delete all governorates permanently ?'),
            'confirmButtonText' => __('admin/deliveriesPages.Delete'),
            'denyButtonText' => __('admin/deliveriesPages.Cancel'),
            'denyButtonColor' => 'gray',
            'confirmButtonColor' => 'red',
            'focusDeny' => false,
            'icon' => 'warning',
            'method' => 'forceDeleteAllGovernorates',
            'governorate_id' => ''
        ]);
    }

    public function forceDeleteAllGovernorates()
    {
        try {
            Governorate::onlyTrashed()->forceDelete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/deliveriesPages.All governorates have been deleted successfully'),
                'icon' => 'info'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/deliveriesPages.All governorates haven\'t been deleted'),
                'icon' => 'error'
            ]);
        }
    }
    ######## Force Delete All #########

    ######## Restore All #########

    public function restoreAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/deliveriesPages.Are you sure, you want to restore all governorates ?'),
            'confirmButtonText' => __('admin/deliveriesPages.Confirm'),
            'denyButtonText' => __('admin/deliveriesPages.Cancel'),
            'denyButtonColor' => 'gray',
            'confirmButtonColor' => 'green',
            'focusDeny' => false,
            'icon' => 'warning',
            'method' => 'restoreAllGovernorates',
            'governorate_id' => '',
        ]);
    }

    public function restoreAllGovernorates()
    {
        try {
            $governorate = Governorate::onlyTrashed()->restore();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/deliveriesPages.All governorates have been restored successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/deliveriesPages.All governorates haven\'t been restored'),
                'icon' => 'error'
            ]);
        }
    }
    ######## Restore All #########

}
