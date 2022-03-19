<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.governorates.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.governorates.create');
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
     * @param  \App\Models\Governorate  $governorate
     * @return \Illuminate\Http\Response
     */
    public function show(Governorate $governorate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Governorate  $governorate
     * @return \Illuminate\Http\Response
     */
    public function edit($governorate)
    {
        return view('admin.governorates.edit',compact('governorate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Governorate  $governorate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Governorate $governorate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Governorate  $governorate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Governorate $governorate)
    {
        //
    }

    public function softDeletedGovernorates()
    {
        return view('admin.governorates.softDeleted');
    }

    public function citiesGovernorate($governorate)
    {
        $governorate = Governorate::withTrashed()->findOrFail($governorate);

        return view('admin.governorates.cities',compact('governorate'));
    }

    public function usersGovernorate($governorate)
    {
        $governorate = Governorate::withTrashed()->findOrFail($governorate);

        return view('admin.governorates.users',compact('governorate'));
    }

    public function deliveriesGovernorate($governorate)
    {
        $governorate = Governorate::withTrashed()->findOrFail($governorate);

        return view('admin.governorates.deliveries',compact('governorate'));
    }


}
