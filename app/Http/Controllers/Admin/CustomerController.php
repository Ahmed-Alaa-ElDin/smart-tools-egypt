<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\Users\CustomerExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.customers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customers.create');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Exports all user from user datatable as xlsx
     *
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
        return Excel::download(new CustomerExport, 'Customers-' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

    /**
     * Exports all user from user datatable as pdf
     *
     * @return \Illuminate\Http\Response
     */
    public function exportPDF()
    {
        return Excel::download(new CustomerExport, 'Customers-' . Carbon::now()->format('d-m-Y') . '.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function softDeletedUsers()
    {
        return view('admin.customers.softDeleted');
    }
}
