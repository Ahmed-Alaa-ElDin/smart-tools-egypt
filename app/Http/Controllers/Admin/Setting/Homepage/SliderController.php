<?php

namespace App\Http\Controllers\Admin\Setting\Homepage;

use App\Models\Banner;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('admin.setting.homepage.sliders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $banners = Banner::all();

        return view('admin.setting.homepage.sliders.create', compact('banners'));
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
     * @param  \App\Models\Banner  $homepageBanner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $homepageBanner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $homepageBanner
     * @return \Illuminate\Http\Response
     */
    public function edit($banner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Banner  $homepageBanner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $homepageBanner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $homepageBanner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $homepageBanner)
    {
        //
    }
}
