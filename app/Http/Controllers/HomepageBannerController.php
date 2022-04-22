<?php

namespace App\Http\Controllers;

use App\Models\HomepageBanner;
use Illuminate\Http\Request;

class HomepageBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.homepage.slider.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\HomepageBanner  $homepageBanner
     * @return \Illuminate\Http\Response
     */
    public function show(HomepageBanner $homepageBanner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HomepageBanner  $homepageBanner
     * @return \Illuminate\Http\Response
     */
    public function edit(HomepageBanner $homepageBanner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HomepageBanner  $homepageBanner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HomepageBanner $homepageBanner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HomepageBanner  $homepageBanner
     * @return \Illuminate\Http\Response
     */
    public function destroy(HomepageBanner $homepageBanner)
    {
        //
    }
}
