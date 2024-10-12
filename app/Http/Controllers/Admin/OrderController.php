<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return view('admin.orders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('admin.orders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     */
    public function show($order_id)
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

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     */
    public function destroy(Order $order)
    {
        //
    }

    /**
     * Display a listing of the new orders.
     */
    public function newOrders()
    {
        return view('admin.orders.new_orders');
    }

    /**
     * Display a listing of the approved orders.
     */
    public function approvedOrders()
    {
        return view('admin.orders.approved_orders');
    }

    /**
     * Display a listing of the edited orders.
     */
    public function editedOrders()
    {
        return view('admin.orders.edited_orders');
    }

    /**
     * Display a listing of the ready for shipping orders.
     */
    public function readyForShipping()
    {
        return view('admin.orders.ready_for_shipping_orders');
    }

    /**
     * Display a listing of the shipped orders.
     */
    public function shippedOrders()
    {
        return view('admin.orders.shipped_orders');
    }

    /**
     * Display a listing of the suspended orders.
     */
    public function suspendedOrders()
    {
        return view('admin.orders.suspended_orders');
    }

    /**
     * Display a listing of the delivered orders.
     */
    public function deliveredOrders()
    {
        return view('admin.orders.delivered_orders');
    }

    /**
     * Display a listing of the history of payments.
     */
    public function paymentHistory($order_id)
    {
        return view('admin.orders.payment_history', compact('order_id'));
    }

    /**
     * Display a listing of the soft deleted orders.
     */
    public function softDeletedOrders()
    {
        return view('admin.orders.softDeleted');
    }
}
