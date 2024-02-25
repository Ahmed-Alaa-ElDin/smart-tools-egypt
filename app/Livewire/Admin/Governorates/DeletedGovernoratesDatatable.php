<?php

namespace App\Livewire\Admin\Governorates;

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
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'governorates.name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $governorates = Governorate::onlyTrashed()->with('country','deliveries','users','cities')
            ->join('countries', 'countries.id', '=', 'governorates.country_id')
            ->select('governorates.*', 'countries.name as country_name')
            ->withCount('users')
            ->withCount('deliveries')
            ->withCount('cities')
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
        $this->dispatch('swalConfirm', text: __('admin/deliveriesPages.Are you sure, you want to delete this governorate permanently ?'),
            confirmButtonText: __('admin/deliveriesPages.Delete'),
            denyButtonText: __('admin/deliveriesPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'forceDeleteGovernorate',
            id: $governorate_id);
    }

    public function forceDeleteGovernorate($id)
    {
        try {
            $governorate = Governorate::onlyTrashed()->findOrFail($id);

            $governorate->forceDelete();

            $this->dispatch('swalDone', text: __('admin/deliveriesPages.Governorate has been deleted permanently successfully'),
                icon: 'info');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/deliveriesPages.Governorate hasn't been deleted permanently"),
                icon: 'error');
        }
    }
    ######## Force Delete #########

    ######## Restore #########
    public function restoreConfirm($governorate_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/deliveriesPages.Are you sure, you want to restore this governorate ?'),
            confirmButtonText: __('admin/deliveriesPages.Confirm'),
            denyButtonText: __('admin/deliveriesPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            icon: 'warning',
            method: 'restoreGovernorate',
            id: $governorate_id);
    }

    public function restoreGovernorate($id)
    {
        try {
            $governorate = Governorate::onlyTrashed()->findOrFail($id);

            $governorate->restore();

            $this->dispatch('swalDone', text: __('admin/deliveriesPages.Governorate has been restored successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/deliveriesPages.Governorate hasn't been restored"),
                icon: 'error');
        }
    }
    ######## Restore #########


    ######## Force Delete All #########
    public function forceDeleteAllConfirm()
    {
        $this->dispatch('swalConfirm', text: __('admin/deliveriesPages.Are you sure, you want to delete all governorates permanently ?'),
            confirmButtonText: __('admin/deliveriesPages.Delete'),
            denyButtonText: __('admin/deliveriesPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'forceDeleteAllGovernorates',
            id: '');
    }

    public function forceDeleteAllGovernorates()
    {
        try {
            Governorate::onlyTrashed()->forceDelete();

            $this->dispatch('swalDone', text: __('admin/deliveriesPages.All governorates have been deleted successfully'),
                icon: 'info');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __('admin/deliveriesPages.All governorates haven\'t been deleted'),
                icon: 'error');
        }
    }
    ######## Force Delete All #########

    ######## Restore All #########

    public function restoreAllConfirm()
    {
        $this->dispatch('swalConfirm', text: __('admin/deliveriesPages.Are you sure, you want to restore all governorates ?'),
            confirmButtonText: __('admin/deliveriesPages.Confirm'),
            denyButtonText: __('admin/deliveriesPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            icon: 'warning',
            method: 'restoreAllGovernorates',
            id: '');
    }

    public function restoreAllGovernorates()
    {
        try {
            $governorate = Governorate::onlyTrashed()->restore();

            $this->dispatch('swalDone', text: __('admin/deliveriesPages.All governorates have been restored successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __('admin/deliveriesPages.All governorates haven\'t been restored'),
                icon: 'error');
        }
    }
    ######## Restore All #########

}
