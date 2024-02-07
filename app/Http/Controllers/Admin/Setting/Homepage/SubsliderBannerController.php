<?php

namespace App\Http\Controllers\Admin\Setting\Homepage;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\SubsliderBanner;
use Illuminate\Http\Request;

class SubsliderBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.setting.homepage.subslider-banners.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $banners = Banner::all();

        return view('admin.setting.homepage.subslider-banners.create', compact('banners'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SubsliderBanner $subsliderBanner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubsliderBanner $subsliderBanner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubsliderBanner $subsliderBanner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubsliderBanner $subsliderBanner)
    {
        //
    }
}
