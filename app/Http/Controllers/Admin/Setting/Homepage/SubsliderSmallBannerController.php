<?php

namespace App\Http\Controllers\Admin\Setting\Homepage;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Models\SubsliderSmallBanner;

class SubsliderSmallBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.setting.homepage.subslider-small-banners.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $banners = Banner::all();

        return view('admin.setting.homepage.subslider-small-banners.create', compact('banners'));
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
    public function show(SubsliderSmallBanner $subsliderSmallBanner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubsliderSmallBanner $subsliderSmallBanner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubsliderSmallBanner $subsliderSmallBanner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubsliderSmallBanner $subsliderSmallBanner)
    {
        //
    }
}
