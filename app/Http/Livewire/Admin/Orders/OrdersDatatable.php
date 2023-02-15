<?php

namespace App\Http\Livewire\Admin\Orders;

use App\Models\Order;
use App\Models\Status;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersDatatable extends Component
{
    use WithPagination;

    protected $listeners = [
        'archiveOrder',
        'statusUpdate',
        'archiveAll',
        'statusesUpdate',
        // 'softDeleteAllProduct',
        // 'publishAllProduct',
        // 'hideAllProduct'
    ];

    // Before First Render
    public function mount()
    {
        $this->perPage = Config::get('constants.constants.PAGINATION');

        $this->sortBy = 'orders.id';

        $this->sortDirection = 'DESC';

        $this->search = "";

        $this->selectedOrders = [];

        $this->selectAll = false;
    }

    public function render()
    {
        $orders = Order::with([
            'user' => fn ($q) => $q->select('id', 'f_name', 'l_name')
                ->with([
                    'phones' => fn ($q) => $q->select('id', 'user_id', 'phone', 'default')->where('default', 1)
                ]),
            'address' => fn ($q) => $q->select('id', 'governorate_id', 'city_id')->with([
                'governorate' => fn ($q) => $q->select('id', 'name'),
                'city' => fn ($q) => $q->select('id', 'name'),
            ]),
            'status',
        ])->select([
            'orders.id as id',
            'orders.user_id',
            'orders.address_id',
            'orders.status_id',
            'orders.total',
            'orders.should_pay',
            'orders.should_get',
            'orders.updated_at',
            'users.f_name',
            'users.l_name',
            'statuses.name as status_name',
            'governorates.name as governorate_name',
        ])
            ->leftJoin('users', 'users.id', '=', 'orders.user_id')
            ->leftJoin('statuses', 'statuses.id', '=', 'orders.status_id')
            ->leftJoin('addresses', 'addresses.id', '=', 'orders.address_id')
            ->leftJoin('governorates', 'governorates.id', '=', 'addresses.governorate_id')
            ->where(
                fn ($q) => $q
                    ->where('orders.id', 'like', '%' . $this->search . '%')
                    ->orWhereHas(
                        'user',
                        fn ($q) => $q
                            ->where('f_name->en', 'like', '%' . $this->search . '%')
                            ->orWhere('f_name->ar', 'like', '%' . $this->search . '%')
                            ->orWhere('l_name->en', 'like', '%' . $this->search . '%')
                            ->orWhere('l_name->ar', 'like', '%' . $this->search . '%')
                            ->orWhereHas('phones', fn ($q) => $q->where('phone', 'like', '%' . $this->search . '%'))
                    )
                    ->orWhereHas(
                        'address',
                        fn ($q) => $q
                            ->whereHas(
                                'city',
                                fn ($q) => $q
                                    ->where('name->en', 'like', '%' . $this->search . '%')
                                    ->orWhere('name->ar', 'like', '%' . $this->search . '%')
                            )
                            ->orWhereHas(
                                'governorate',
                                fn ($q) => $q
                                    ->where('name->en', 'like', '%' . $this->search . '%')
                                    ->orWhere('name->ar', 'like', '%' . $this->search . '%')
                            )
                    )
                    ->orWhereHas(
                        'status',
                        fn ($q) => $q
                            ->where('name', 'like', '%' . $this->search . '%')
                    )
            )
            ->orWhereIn('orders.id', $this->selectedOrders)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage + count($this->selectedOrders));

        $this->orders_ids = $orders->pluck('id')->toArray();
        // dd($orders);

        return view('livewire.admin.orders.orders-datatable', compact('orders'));
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

    // reset pagination after new search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // select all orders
    public function updatedSelectedOrders()
    {
        $this->selectAll = count($this->selectedOrders) == count($this->orders_ids) ? true : false;
    }

    // select all orders
    public function updatedSelectAll()
    {
        $this->selectedOrders = $this->selectAll ? $this->orders_ids : [];
    }

    // unselect all orders
    public function unselectAll()
    {
        $this->selectedOrders = [];
        $this->selectAll = [];
    }
    ######## Archive #########
    public function archiveConfirm($order_id)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/ordersPages.Are you sure, you want to archive this order?'),
            'confirmButtonText' => __('admin/ordersPages.Yes'),
            'denyButtonText' => __('admin/ordersPages.No'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'archiveOrder',
            'id' => $order_id,
        ]);
    }

    public function archiveOrder($order_id)
    {
        try {
            $order = Order::findOrFail($order_id);
            $order->delete();

            if (($key = array_search($order_id, $this->selectedOrders)) !== false) {
                unset($this->selectedOrders[$key]);
            }

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/ordersPages.Order has been archived successfully'),
                'icon' => 'success'
            ]);

            $this->selectedProducts = [];
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/ordersPages.Order hasn't been archived"),
                'icon' => 'error'
            ]);
        }
    }

    public function archiveAllConfirm()
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/ordersPages.Are you sure, you want to archive these orders?'),
            'confirmButtonText' => __('admin/ordersPages.Yes'),
            'denyButtonText' => __('admin/ordersPages.No'),
            'denyButtonColor' => 'green',
            'confirmButtonColor' => 'red',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'archiveAll',
            'id' => '',
        ]);
    }

    public function archiveAll()
    {
        try {
            Order::whereIn('id', $this->selectedOrders)->delete();

            $this->selectedOrders = [];

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __('admin/ordersPages.Orders have been archived successfully'),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/ordersPages.Order haven't been archived"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Archive #########

    ######## Status #########
    public function statusUpdateSelect($order_id)
    {
        $this->dispatchBrowserEvent('swalSelectBox', [
            'title' => __('admin/ordersPages.Select Order Status'),
            'confirmButtonText' => __('admin/ordersPages.Update'),
            'denyButtonText' => __('admin/ordersPages.Cancel'),
            'data' => json_encode(Status::get()->pluck('name', 'id')),
            'selected' => Order::findOrFail($order_id)->statuses()->orderBy('pivot_created_at', 'desc')->first()->id ?? null,
            'denyButtonColor' => 'red',
            'confirmButtonColor' => 'green',
            'focusDeny' => false,
            'method' => 'statusUpdate',
            'id' => $order_id,
        ]);
    }

    public function statusUpdate($order_id, $status_id)
    {
        try {
            $order = Order::findOrFail($order_id);

            $order->statuses()->attach($status_id);

            $order->update([
                'status_id' => $status_id,
                'delivered_at' => $status_id == 45 ? now() : null
            ]);

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/ordersPages.Order's status has been updated successfully"),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/ordersPages.Order's status hasn't been updated"),
                'icon' => 'error'
            ]);
        }
    }

    public function statusesUpdateSelect()
    {
        $this->dispatchBrowserEvent('swalSelectBox', [
            'title' => __('admin/ordersPages.Select Order Status'),
            'confirmButtonText' => __('admin/ordersPages.Update'),
            'denyButtonText' => __('admin/ordersPages.Cancel'),
            'data' => json_encode(Status::get()->pluck('name', 'id')),
            'selected' => Status::first() ? Status::first()->id : null,
            'denyButtonColor' => 'red',
            'confirmButtonColor' => 'green',
            'focusDeny' => false,
            'method' => 'statusesUpdate',
            'id' => '',
        ]);
    }

    public function statusesUpdate($order_id, $status_id)
    {
        try {
            $orders = Order::whereIn('id', $this->selectedOrders);

            $orders->each(function ($q) use ($status_id) {
                $q->statuses()->attach($status_id);

                $q->update([
                    'status_id' => $status_id,
                    'delivered_at' => $status_id == 45 ? now() : null
                ]);
            });

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/ordersPages.Orders' statuses have been updated successfully"),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/ordersPages.Orders' statuses haven't been updated"),
                'icon' => 'error'
            ]);
        }
    }
    ######## Status #########

}
