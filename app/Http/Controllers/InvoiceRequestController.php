<?php

namespace App\Http\Controllers;

use App\Models\InvoiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            InvoiceRequest::create([
                'user_id' => $request['user_id'],
                'order_id' => $request['order_id'],
                'status' => 0,
            ]);

            DB::commit();

            return redirect()->back()->with('success', __('front/homePage.Invoice request has been sent successfully'));
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->back()->with('error', __('front/homePage.Something went wrong'));
        }
    }
}
