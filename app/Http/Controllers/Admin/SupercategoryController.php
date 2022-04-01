<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supercategory;
use Illuminate\Http\Request;

class SupercategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.supercategories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.supercategories.create');
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
     * @param  \App\Models\Supercategory  $supercategory
     * @return \Illuminate\Http\Response
     */
    public function show(Supercategory $supercategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supercategory  $supercategory
     * @return \Illuminate\Http\Response
     */
    public function edit($supercategory)
    {
        return view('admin.supercategories.edit',compact('supercategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supercategory  $supercategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supercategory $supercategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supercategory  $supercategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supercategory $supercategory)
    {
        //
    }

    public function softDeletedSupercategories()
    {
        return view('admin.supercategories.softDeleted');
    }

}
