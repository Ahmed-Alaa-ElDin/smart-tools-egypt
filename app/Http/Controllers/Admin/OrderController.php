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

    public function paymentHistory($order_id)
    {
        return view('admin.orders.payment_history', compact('order_id'));
    }

    public function softDeletedOrders()
    {
        return view('admin.orders.softDeleted');
    }
}
