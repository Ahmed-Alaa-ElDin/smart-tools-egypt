<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Throwable;

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
        return view('admin.governorates.edit', compact('governorate'));
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

        return view('admin.governorates.cities', compact('governorate'));
    }

    public function usersGovernorate($governorate)
    {
        $governorate = Governorate::withTrashed()->findOrFail($governorate);

        return view('admin.governorates.users', compact('governorate'));
    }

    public function deliveriesGovernorate($governorate)
    {
        $governorate = Governorate::withTrashed()->findOrFail($governorate);

        return view('admin.governorates.deliveries', compact('governorate'));
    }

    public function getBostaGovernorate()
    {
        DB::beginTransaction();

        try {
            $encoded_api_governorates = Http::acceptJson()->get('https://app.bosta.co/api/v2/cities?countryId=60e4482c7cb7d4bc4849c4d5')->body();
            $decoded_api_governorates = json_decode($encoded_api_governorates);

            foreach ($decoded_api_governorates->data->list as $governorate) {
                Governorate::updateOrCreate(
                    ['bosta_id' => $governorate->_id],
                    [
                        'name' => [
                            'en' => $governorate->name,
                            'ar' => $governorate->nameAr,
                        ],
                        'country_id' => 1,
                    ]
                );
            }

            DB::commit();

            Session::flash('success', __('admin/deliveriesPages.Governorates Imported successfully'));

            return redirect()->route('admin.governorates.index');
        } catch (Throwable $th) {
            DB::rollBack();

            Session::flash('error', __("admin/deliveriesPages.Governorates haven't been Imported"));
            return redirect()->route('admin.governorates.index');
        }
    }
}
