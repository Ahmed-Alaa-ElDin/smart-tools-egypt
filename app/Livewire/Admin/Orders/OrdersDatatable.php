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
use Illuminate\Support\Facades\Storage;
use App\Services\Front\Deliveries\Bosta;
use App\Services\Front\Deliveries\DeliveryService;

class OrdersDatatable extends Component
{
    use WithPagination;

    public $type;
    public $sortBy;
    public $sortDirection;
    public $perPage;
    public $search;
    public $selectedOrders;
    public $selectAll;
    public $orders_ids;
    public $selectedProducts;

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
            'orders.order_delivery_id',
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
            ->when($this->type != 'all_orders', fn ($q) => $q->whereIn('orders.status_id', config("constants.order_status_type.$this->type")))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $this->orders_ids = $orders->pluck('id')->toArray();

        $order = $orders->map(function ($order) {
            $order->should_pay =  $order->transactions->where('payment_status_id', PaymentStatus::Pending->value)->sum('payment_amount');
            $order->should_get = $order->transactions->where('payment_status_id', PaymentStatus::Refundable->value)->sum('payment_amount');
            return $order;
        });

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
        $this->dispatch(
            'swalConfirm',
            text: __('admin/ordersPages.Are you sure, you want to archive this order?'),
            confirmButtonText: __('admin/ordersPages.Yes'),
            denyButtonText: __('admin/ordersPages.No'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'archiveOrder',
            id: $order_id
        );
    }

    public function archiveOrder($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            if (($key = array_search($id, $this->selectedOrders)) !== false) {
                unset($this->selectedOrders[$key]);
            }

            $this->dispatch(
                'swalDone',
                text: __('admin/ordersPages.Order has been archived successfully'),
                icon: 'success'
            );

            $this->selectedProducts = [];
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Order hasn't been archived"),
                icon: 'error'
            );
        }
    }

    public function archiveAllConfirm()
    {
        $this->dispatch(
            'swalConfirm',
            text: __('admin/ordersPages.Are you sure, you want to archive these orders?'),
            confirmButtonText: __('admin/ordersPages.Yes'),
            denyButtonText: __('admin/ordersPages.No'),
            denyButtonColor: 'green',
            confirmButtonColor: 'red',
            focusDeny: true,
            icon: 'warning',
            method: 'archiveAll',
            id: ''
        );
    }

    public function archiveAll()
    {
        try {
            Order::whereIn('id', $this->selectedOrders)->delete();

            $this->selectedOrders = [];

            $this->dispatch(
                'swalDone',
                text: __('admin/ordersPages.Orders have been archived successfully'),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            // throw $th;
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Orders haven't been archived"),
                icon: 'error'
            );
        }
    }
    ######## Archive #########

    ######## Status #########
    public function statusUpdateSelect($order_id)
    {
        $currentStatus = Order::findOrFail($order_id)->statuses()->orderBy('pivot_created_at', 'desc')->first()->id ?? null;

        $this->dispatch(
            'swalSelectBox',
            title: __('admin/ordersPages.Select Order Status'),
            confirmButtonText: __('admin/ordersPages.Update'),
            denyButtonText: __('admin/ordersPages.Cancel'),
            data: json_encode(Status::whereIn('id', Config::get("constants.order_status_options.$this->type"))->orWhere('id', $currentStatus)
                ->get()
                ->pluck('name', 'id')),
            selected: $currentStatus,
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            method: 'statusUpdate',
            id: $order_id
        );
    }

    public function statusUpdate($id, $status_id)
    {
        try {
            $order = Order::findOrFail($id);

            $order->statuses()->attach($status_id);

            $order->update([
                'status_id' => $status_id,
                'delivered_at' => $status_id == OrderStatus::Delivered->value ? now() : null
            ]);

            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Order's status has been updated successfully"),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Order's status hasn't been updated"),
                icon: 'error'
            );
        }
    }

    public function statusesUpdateSelect()
    {
        $this->dispatch(
            'swalSelectBox',
            title: __('admin/ordersPages.Select Order Status'),
            confirmButtonText: __('admin/ordersPages.Update'),
            denyButtonText: __('admin/ordersPages.Cancel'),
            data: json_encode(Status::whereIn('id', Config::get("constants.order_status_options.$this->type"))
                ->get()
                ->pluck('name', 'id')),
            selected: null,
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: false,
            method: 'statusesUpdate',
            id: ''
        );
    }

    public function statusesUpdate($id, $status_id)
    {
        try {
            $orders = Order::whereIn('id', $this->selectedOrders);

            $orders->each(function ($q) use ($status_id) {
                $q->statuses()->attach($status_id);

                $q->update([
                    'status_id' => $status_id,
                    'delivered_at' => $status_id == OrderStatus::Delivered->value ? now() : null
                ]);
            });

            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Orders' statuses have been updated successfully"),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Orders' statuses haven't been updated"),
                icon: 'error'
            );
        }
    }
    ######## Status #########

    ######## Create Delivery #########
    public function createBostaOrder($orderId)
    {
        $order = Order::findOrFail($orderId);

        if (!$order->order_delivery_id) {
            $bosta_order = createBostaOrder($order);

            if ($bosta_order['status']) {
                $this->dispatch(
                    'swalDone',
                    text: __("admin/ordersPages.The delivery has been created successfully"),
                    icon: 'success'
                );

                $order->update([
                    'tracking_number' => $bosta_order['data']['trackingNumber'],
                    'order_delivery_id' => $bosta_order['data']['_id'],
                ]);
            } else {
                $this->dispatch(
                    'swalDone',
                    text: __("admin/ordersPages.The delivery hasn't been created"),
                    icon: 'error'
                );
            }
        } else {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.The delivery has been created before"),
                icon: 'error'
            );
        }
    }
    ######## Create Delivery #########

    ######## Download Bosta AWB #########
    public function downloadBostaAWB($deliveryId)
    {
        try {
            $bostaClient = new Bosta();
            $deliveryService = new DeliveryService($bostaClient);

            // Get the AWB as base64
            $awbs = $deliveryService->getAWBs([$deliveryId]);

            if (!empty($awbs)) {
                // Convert the base64 to PDF
                $awbPdf = base64_decode($awbs);

                // Generate the path
                $path = "awbs/" . date('Y-m-d') . "/" . $deliveryId . ".pdf";

                // Save the PDF
                Storage::disk('public')->put($path, $awbPdf);

                // Download the PDF
                return Storage::disk('public')->download($path);
            } else {
                $this->dispatch(
                    'swalDone',
                    text: __("admin/ordersPages.Couldn't download the AWB"),
                    icon: 'error'
                );
            }
        } catch (\Exception $e) {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Couldn't download the AWB"),
                icon: 'error'
            );
        }
    }
    ######## Download Bosta AWB #########

    ######## Bulk Download Bosta AWB #########
    public function downloadBostaAWBs()
    {
        try {
            $bostaClient = new Bosta();
            $deliveryService = new DeliveryService($bostaClient);

            // Get the delivery ids from the selected orders
            $deliveryIds = Order::whereIn('id', $this->selectedOrders)->pluck('order_delivery_id')->toArray();

            // Get the AWB as base64
            $awbs = $deliveryService->getAWBs($deliveryIds);

            if (!empty($awbs)) {
                // Convert the base64 to PDF
                $awbPdf = base64_decode($awbs);

                // Generate the path
                $path = "awbs/" . date('Y-m-d') . "/" . implode("-", $deliveryIds) . ".pdf";

                // Save the PDF
                Storage::disk('public')->put($path, $awbPdf);

                // Download the PDF
                return Storage::disk('public')->download($path);
            } else {
                $this->dispatch(
                    'swalDone',
                    text: __("admin/ordersPages.Couldn't download the AWBs"),
                    icon: 'error'
                );
            }
        } catch (\Exception $e) {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Couldn't download the AWBs"),
                icon: 'error'
            );
        }
    }
    ######## Bulk Download Bosta AWB #########

    ######## TODO:: Download Purchase Order #########
    public function downloadPurchaseOrder($order_id)
    {
        $order = Order::with([
            'products' => fn ($q) => $q->with('thumbnail'),
            'collections' => fn ($q) => $q->with('thumbnail'),
            "statuses",
            "invoice",
            "transactions"
        ])
            ->withTrashed()
            ->findOrFail($order_id);

        $order->items = $order->products->merge($order->collections)->toArray();

        $pdf = \PDF::loadView('admin.orders.purchase-order', compact('order'));

        return $pdf->download('purchase_order_' . $order->id . '.pdf');
    }
    ######## Download Purchase Order #########
}
