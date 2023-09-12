<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\Collections\CollectionsExport;
use App\Http\Controllers\Controller;
use App\Models\Collection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.collections.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.collections.create');
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
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function show(Collection $collection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function edit($collection)
    {
        return view('admin.collections.edit', compact('collection'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collection $collection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collection $collection)
    {
        //
    }

    /**
     * Exports all products from products datatable as xlsx
     *
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
        return Excel::download(new CollectionsExport, 'Collection-' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

    /**
     * Exports all products from products datatable as pdf
     *
     * @return \Illuminate\Http\Response
     */
    public function exportPDF()
    {
        return Excel::download(new CollectionsExport, 'Collection-' . Carbon::now()->format('d-m-Y') . '.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    public function softDeletedCollections()
    {
        return view('admin.collections.softDeleted');
    }

    /**
     * Copy Collection
     * @param  int  $collection_id
     */
    public function copy($collection_id) {
        return view('admin.collections.copy')->with('old_collection_id', $collection_id)->with('success', __('admin/productsPages.Collection Copied Successfully'));
    }

}
