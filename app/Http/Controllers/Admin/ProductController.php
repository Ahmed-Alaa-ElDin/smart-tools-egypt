<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\Products\ProductsExports;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.products.create');
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
     * Copy the specified product.
     *
     * @param  \App\Models\Product  $product
     */
    public function copy($product_id)
    {
        return view('admin.products.copy', ['old_product_id' => $product_id])->with('success', __('admin/productsPages.Product Copied Successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
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
        return Excel::download(new ProductsExports, 'Products-' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

    /**
     * Exports all products from products datatable as pdf
     *
     * @return \Illuminate\Http\Response
     */
    public function exportPDF()
    {
        return Excel::download(new ProductsExports, 'Products-' . Carbon::now()->format('d-m-Y') . '.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    /**
     * See Deleted Products
     *
     * @return \Illuminate\Http\Response
     */

    public function softDeletedProducts()
    {
        return view('admin.products.softDeleted');
    }
}
