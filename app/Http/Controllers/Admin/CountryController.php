<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.countries.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.countries.create');
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
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit($country)
    {
        return view('admin.countries.edit',compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        //
    }

    public function softDeletedCountries()
    {
        return view('admin.countries.softDeleted');
    }

    public function governoratesCountry($country)
    {
        $country = Country::withTrashed()->findOrFail($country);

        return view('admin.countries.governorates',compact('country'));
    }

    public function citiesCountry($country)
    {
        $country = Country::withTrashed()->findOrFail($country);

        return view('admin.countries.cities',compact('country'));
    }

    public function usersCountry($country)
    {
        $country = Country::withTrashed()->findOrFail($country);

        return view('admin.countries.users',compact('country'));
    }

    public function deliveriesCountry($country)
    {
        $country = Country::withTrashed()->findOrFail($country);

        return view('admin.countries.deliveries',compact('country'));
    }


}
