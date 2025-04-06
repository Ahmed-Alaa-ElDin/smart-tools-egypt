<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Governorate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.cities.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cities.create');
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
     * @param  \App\Models\Admin\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit($city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        //
    }

    public function softDeletedCities()
    {
        return view('admin.cities.softDeleted');
    }

    public function usersCity(City $city)
    {
        return view('admin.cities.users', compact('city'));
    }

    public function deliveriesCity(City $city)
    {
        return view('admin.cities.deliveries', compact('city'));
    }

    public function getBostaCities()
    {
        $governorates = Governorate::where('country_id', 1)->get();

        DB::beginTransaction();

        try {
            foreach ($governorates as $governorate) {
                $encoded_api_cities = Http::acceptJson()->get('https://app.bosta.co/api/v2/cities/' . $governorate->bosta_id . '/districts')->body();
                $decoded_api_cities = json_decode($encoded_api_cities);

                foreach ($decoded_api_cities->data as $city) {
                    City::updateOrCreate(
                        ['bosta_id' => $city->districtId],
                        [
                            'name' => [
                                'en' => $city->zoneName . ($city->districtName != $city->zoneName ? " - " . $city->districtName : ''),
                                'ar' => $city->zoneOtherName . ($city->districtOtherName != $city->zoneOtherName ? " - " . $city->districtOtherName : ''),
                            ],
                            'governorate_id' => $governorate->id,
                        ]
                    );
                }
            }

            DB::commit();

            Session::flash('success', __('admin/deliveriesPages.Cities Imported successfully'));

            return redirect()->route('admin.cities.index');
        } catch (\Throwable $th) {
            DB::rollBack();

            Session::flash('error', __("admin/deliveriesPages.Cities haven't been Imported"));
            return redirect()->route('admin.cities.index');
        }
    }
}
