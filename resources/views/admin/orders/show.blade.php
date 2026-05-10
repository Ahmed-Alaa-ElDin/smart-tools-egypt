@extends('layouts.admin.admin', [
    'activeSection' => 'Orders',
    'activePage' => 'All Orders',
    'titlePage' => __("admin/ordersPages.Order's Details"),
])

@section('content')
    <div class="content">
        <div class="container-fluid">
            @livewire('admin.orders.order-details', ['order_id' => $order->id])
        </div>
    </div>
@endsection
