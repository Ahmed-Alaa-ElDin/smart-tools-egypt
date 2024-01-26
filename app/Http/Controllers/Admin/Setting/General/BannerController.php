<?php

namespace App\Http\Controllers\Admin\Setting\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        return view('admin.setting.general.banners.index');
    }

    public function create()
    {
        return view('admin.setting.general.banners.create');
    }
    public function edit($banner_id)
    {
        return view('admin.setting.general.banners.edit', compact('banner_id'));
    }

    public function update(Request $request, $banner)
    {
        //
    }

    public function destroy($banner)
    {
        //
    }
}
