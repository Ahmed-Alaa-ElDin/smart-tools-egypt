<?php

namespace App\Http\Livewire\Admin\Deliveries;

use App\Models\Delivery;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class DeliveryCompaniesDatatable extends Component
{
    use WithPagination;

    public $sortBy = 'name';
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    protected $listeners = ['softDeleteDelivery'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');
    }

    // Render With each update
    public function render()
    {
        $deliveries = Delivery::with('phones')
            ->where('name->en', 'like', '%' . $this->search . '%')
            ->orWhere('name->ar', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhereHas('phones', function ($query) {
                $query->where('phone', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.deliveries.delivery-companies-datatable', compact('deliveries'));
    }

    // reset pagination after new search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Add coditions of sorting
    public function sortBy($field)
    {
        if ($this->sortDirection == 'ASC') {
            $this->sortDirection = 'DESC';
        } else {
            $this->sortDirection = 'ASC';
        }

        return $this->sortBy = $field;
    }

    ######## Soft Delete #########
    public function deleteConfirm($delivery_id)
    {
        $this->dispatchBrowserEvent('swalConfirmSoftDelete', [
            "text" => __('admin/deliveriesPages.Are you sure, you want to delete this company ?'),
            'confirmButtonText' => __('admin/deliveriesPages.Delete'),
            'denyButtonText' => __('admin/deliveriesPages.Cancel'),
            'delivery_id' => $delivery_id,
        ]);
    }

    public function softDeleteDelivery($delivery_id)
    {
        try {
            $user = Delivery::findOrFail($delivery_id);
            $user->delete();

            $this->dispatchBrowserEvent('swalDeliveryDeleted', [
                "text" => __('admin/deliveriesPages.Delivery has been deleted successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDeliveryDeleted', [
                "text" => __("admin/deliveriesPages.Delivery hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Soft Delete #########

    ######## Activation Toggle #########
    public function activate($delivery_id)
    {
        // dd($delivery_id);
        $delivery = Delivery::findOrFail($delivery_id);

        try {

            $delivery->is_active = !$delivery->is_active;

            $delivery->save();

            $this->dispatchBrowserEvent('swalDeliveryActivated', [
                "text" => $delivery->is_active ? __('admin/deliveriesPages.Delivery has been activated') : __('admin/deliveriesPages.Delivery has been deactivated'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDeliveryActivated', [
                "text" => $delivery->is_active ? __("admin/deliveriesPages.Delivery hasn't been activated") : __("admin/deliveriesPages.Delivery hasn't been deactivated"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Activation Toggle #########




}
