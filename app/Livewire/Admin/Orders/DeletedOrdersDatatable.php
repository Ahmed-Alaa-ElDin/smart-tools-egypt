<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use App\Models\Status;
use Livewire\Component;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class DeletedOrdersDatatable extends Component
{
    use WithPagination;

    public $sortBy;
    public $sortDirection;
    public $perPage;
    public $search;
    public $selectedOrders;
    public $selectAll;
    public $orders_ids;
    public $selectedProducts;

    protected $listeners = [
        'deleteOrder',
        'restoreOrder',
        'deleteAll',
        'restoreAll',
    ];

    // Before First Render
    public function mount()
    {
        $this->perPage = Config::get('settings.back_pagination');

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
            'invoice' => fn ($q) => $q->select('id', 'order_id', 'total'),
            'transactions',
            'status',
        ])->select([
            'orders.id as id',
            'orders.user_id',
            'orders.address_id',
            'orders.status_id',
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
                            ->where(DB::raw('LOWER(f_name)'), 'like', '%' . strtolower($this->search) . '%')
                            ->orWhere(DB::raw('LOWER(l_name)'), 'like', '%' . strtolower($this->search) . '%')
                            ->orWhereHas('phones', fn ($q) => $q->where('phone', 'like', '%' . strtolower($this->search) . '%'))
                    )
                    ->orWhereHas(
                        'address',
                        fn ($q) => $q
                            ->whereHas(
                                'city',
                                fn ($q) => $q
                                    ->where(DB::raw('LOWER(name)'), 'like', '%' . strtolower($this->search) . '%')
                            )
                            ->orWhereHas(
                                'governorate',
                                fn ($q) => $q
                                    ->where(DB::raw('LOWER(name)'), 'like', '%' . strtolower($this->search) . '%')
                            )
                    )
                    ->orWhereHas(
                        'status',
                        fn ($q) => $q
                            ->where(DB::raw('LOWER(name)'), 'like', '%' . strtolower($this->search) . '%')
                    )
                    ->orWhereIn('orders.id', $this->selectedOrders)
            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->onlyTrashed()
            ->paginate($this->perPage);

        $this->orders_ids = $orders->pluck('id')->toArray();

        $order = $orders->map(function ($order) {
            $order->should_pay =  $order->transactions->where('payment_status_id', PaymentStatus::Pending->value)->sum('payment_amount');
            $order->should_get = $order->transactions->where('payment_status_id', PaymentStatus::Refundable->value)->sum('payment_amount');
            return $order;
        });

        return view('livewire.admin.orders.deleted-orders-datatable', compact('orders'));
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
    ######## Force Delete #########
    public function deleteConfirm($order_id)
    {
        $this->dispatch(
            'swalConfirm',
            text: __('admin/ordersPages.Are you sure, you want to delete this order?'),
            confirmButtonText: __('admin/ordersPages.Yes'),
            denyButtonText: __('admin/ordersPages.No'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'deleteOrder',
            id: $order_id
        );
    }

    public function deleteOrder($id)
    {
        try {
            $order = Order::onlyTrashed()->findOrFail($id);
            $order->forceDelete();

            if (($key = array_search($id, $this->selectedOrders)) !== false) {
                unset($this->selectedOrders[$key]);
            }

            $this->dispatch(
                'swalDone',
                text: __('admin/ordersPages.Order has been deleted successfully'),
                icon: 'success'
            );

            $this->selectedProducts = [];
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Order has not been deleted"),
                icon: 'error'
            );
        }
    }

    public function deleteAllConfirm()
    {
        $this->dispatch(
            'swalConfirm',
            text: __('admin/ordersPages.Are you sure, you want to delete these orders?'),
            confirmButtonText: __('admin/ordersPages.Yes'),
            denyButtonText: __('admin/ordersPages.No'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'deleteAll',
            id: ''
        );
    }

    public function deleteAll()
    {
        try {
            Order::whereIn('id', $this->selectedOrders)->onlyTrashed()->forceDelete();
            $this->selectedOrders = [];

            $this->dispatch(
                'swalDone',
                text: __('admin/ordersPages.Orders have been deleted successfully'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Orders haven't been deleted"),
                icon: 'error'
            );
        }
    }
    ######## Archive #########

    ######## Restore #########
    public function restoreConfirm($order_id)
    {
        $this->dispatch(
            'swalConfirm',
            text: __('admin/ordersPages.Are you sure, you want to restore this order?'),
            confirmButtonText: __('admin/ordersPages.Yes'),
            denyButtonText: __('admin/ordersPages.No'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            icon: 'warning',
            method: 'restoreOrder',
            id: $order_id
        );
    }

    public function restoreOrder($id)
    {
        try {
            $order = Order::onlyTrashed()->findOrFail($id);
            $order->restore();

            if (($key = array_search($id, $this->selectedOrders)) !== false) {
                unset($this->selectedOrders[$key]);
            }

            $this->dispatch(
                'swalDone',
                text: __('admin/ordersPages.Order has been restored successfully'),
                icon: 'success'
            );

            $this->selectedProducts = [];
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Order has not been restored"),
                icon: 'error'
            );
        }
    }

    public function restoreAllConfirm()
    {
        $this->dispatch(
            'swalConfirm',
            text: __('admin/ordersPages.Are you sure, you want to restore these orders?'),
            confirmButtonText: __('admin/ordersPages.Yes'),
            denyButtonText: __('admin/ordersPages.No'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            icon: 'warning',
            method: 'restoreAll',
            id: ''
        );
    }

    public function restoreAll()
    {
        try {
            Order::whereIn('id', $this->selectedOrders)->onlyTrashed()->restore();

            $this->selectedOrders = [];

            $this->dispatch(
                'swalDone',
                text: __('admin/ordersPages.Orders have been restored successfully'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Orders haven't been restored"),
                icon: 'error'
            );
        }
    }
    ######## Restore #########

}
