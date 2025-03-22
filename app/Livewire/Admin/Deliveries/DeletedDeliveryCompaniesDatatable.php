<?php

namespace App\Livewire\Admin\Deliveries;

use App\Models\Delivery;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class DeletedDeliveryCompaniesDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['forceDeleteDelivery', 'restoreDelivery', 'forceDeleteAllDeliveries', 'restoreAllDeliveries'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $deliveries = Delivery::onlyTrashed()->with('phones')
            ->where(function ($query) {
                return $query->where('name->en', 'like', '%' . $this->search . '%')
                    ->orWhere('name->ar', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas('phones', function ($query) {
                        $query->where('phone', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.deliveries.deleted-delivery-companies-datatable', compact('deliveries'));
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

    ######## Force Delete #########
    public function forceDeleteConfirm($delivery_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/deliveriesPages.Are you sure, you want to delete this delivery permanently ?'),
            confirmButtonText: __('admin/deliveriesPages.Delete'),
            denyButtonText: __('admin/deliveriesPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'forceDeleteDelivery',
            id: $delivery_id);
    }

    public function forceDeleteDelivery($id)
    {
        try {
            $delivery = Delivery::onlyTrashed()->findOrFail($id);

            $delivery->forceDelete();

            $this->dispatch('swalDone', text: __('admin/deliveriesPages.Delivery has been deleted permanently successfully'),
                icon: 'info');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/deliveriesPages.Delivery has not been deleted permanently"),
                icon: 'error');
        }
    }
    ######## Force Delete #########

    ######## Restore #########
    public function restoreConfirm($delivery_id)
    {
        $this->dispatch('swalConfirm', text: __('admin/deliveriesPages.Are you sure, you want to restore this delivery ?'),
            confirmButtonText: __('admin/deliveriesPages.Confirm'),
            denyButtonText: __('admin/deliveriesPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            icon: 'warning',
            method: 'restoreDelivery',
            id: $delivery_id);
    }

    public function restoreDelivery($id)
    {
        try {
            $delivery = Delivery::onlyTrashed()->findOrFail($id);

            $delivery->restore();

            $this->dispatch('swalDone', text: __('admin/deliveriesPages.Delivery has been restored successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __("admin/deliveriesPages.Delivery has not been restored"),
                icon: 'error');
        }
    }
    ######## Restore #########


    ######## Force Delete All #########
    public function forceDeleteAllConfirm()
    {
        $this->dispatch('swalConfirm', text: __('admin/deliveriesPages.Are you sure, you want to delete all deliveries permanently ?'),
            confirmButtonText: __('admin/deliveriesPages.Delete'),
            denyButtonText: __('admin/deliveriesPages.Cancel'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'forceDeleteAllDeliveries',
            id: '');
    }

    public function forceDeleteAllDeliveries()
    {
        try {
            Delivery::onlyTrashed()->forceDelete();

            $this->dispatch('swalDone', text: __('admin/deliveriesPages.All deliveries have been deleted successfully'),
                icon: 'info');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __('admin/deliveriesPages.All deliveries haven\'t been deleted'),
                icon: 'error');
        }
    }
    ######## Force Delete All #########

    ######## Restore All #########

    public function restoreAllConfirm()
    {
        $this->dispatch('swalConfirm', text: __('admin/deliveriesPages.Are you sure, you want to restore all deliveries ?'),
            confirmButtonText: __('admin/deliveriesPages.Confirm'),
            denyButtonText: __('admin/deliveriesPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            icon: 'warning',
            method: 'restoreAllDeliveries',
            id: '');
    }

    public function restoreAllDeliveries()
    {
        try {
            $delivery = Delivery::onlyTrashed()->restore();

            $this->dispatch('swalDone', text: __('admin/deliveriesPages.All deliveries have been restored successfully'),
                icon: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('swalDone', text: __('admin/deliveriesPages.All deliveries haven\'t been restored'),
                icon: 'error');
        }
    }
    ######## Restore All #########

}
