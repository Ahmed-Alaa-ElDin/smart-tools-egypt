<?php

namespace App\Http\Controllers\Admin\Setting\General;

use App\Http\Controllers\Controller;
use App\Models\NavLink;
use Illuminate\Http\Request;

class NavLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.setting.general.nav-links.index');
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
     * @param  \App\Models\NavLink  $navLink
     * @return \Illuminate\Http\Response
     */
    public function show(NavLink $navLink)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NavLink  $navLink
     * @return \Illuminate\Http\Response
     */
    public function edit(NavLink $navLink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NavLink  $navLink
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NavLink $navLink)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NavLink  $navLink
     * @return \Illuminate\Http\Response
     */
    public function destroy(NavLink $navLink)
    {
        //
    }
}
