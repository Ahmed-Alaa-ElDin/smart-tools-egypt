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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.orders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function paymentHistory($order_id)
    {
        $order = Order::with([
            'payments',
            'user' => fn ($q) => $q->with([
                'phones' => fn ($q) => $q->where('default', 1)
            ])->select('id', 'f_name', 'l_name')
        ])->findOrFail($order_id);

        $order->paid = $order->payments->where('payment_status', 2)->where('payment_amount', ">=", 0)->sum('payment_amount');
        $order->unpaid = $order->payments->where('payment_status', 1)->where('payment_amount', ">=", 0)->sum('payment_amount');
        $order->refund = $order->payments->where('payment_amount', "<", 0)->sum('payment_amount');

        return view('admin.orders.payment_history', compact('order'));
    }
}
