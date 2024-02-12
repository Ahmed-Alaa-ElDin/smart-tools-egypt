<?php

namespace App\Http\Livewire\Admin\Deliveries;

use App\Models\Delivery;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class DeliveryCompaniesDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection = 'ASC';
    public $perPage;

    public $search = "";

    public $city_id = null;
    public $governorate_id = null;
    public $country_id = null;

    protected $listeners = ['softDeleteDelivery'];

    // Render Once
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

        $this->sortBy = 'name->' . session('locale');
    }

    // Render With each update
    public function render()
    {
        $deliveries = Delivery::with(['phones', 'cities'])
            ->where(fn ($q) => $q
                ->where('name->en', 'like', '%' . $this->search . '%')
                ->orWhere('name->ar', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhereHas('phones', function ($query) {
                    $query->where('phone', 'like', '%' . $this->search . '%');
                }))
                ->where(function ($q){
                    if ($this->city_id) {
                        return $q->whereHas('cities', fn ($q) => $q->where('cities.id', $this->city_id));
                    }
                    if ($this->governorate_id) {
                        return $q->whereHas('governorates', fn ($q) => $q->where('governorates.id', $this->governorate_id));
                    }
                    if ($this->country_id) {
                        return $q->whereHas('countries', fn ($q) => $q->where('countries.id', $this->country_id));
                    }
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
    public function deleteConfirm($delivery_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/deliveriesPages.Are you sure, you want to delete this company ?'),
            'confirmButtonText' => __('admin/deliveriesPages.Delete'),
            'denyButtonText' => __('admin/deliveriesPages.Cancel'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'method' => 'softDeleteDelivery',
            'id' => $delivery_id,
        ]);
    }

    public function softDeleteDelivery($delivery_id)
    {
        try {
            $user = Delivery::findOrFail($delivery_id);
            $user->delete();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/deliveriesPages.Delivery has been deleted successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/deliveriesPages.Delivery hasn't been deleted"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Deleted #########

    ######## Activation Toggle #########
    public function activate($delivery_id)
    {
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
