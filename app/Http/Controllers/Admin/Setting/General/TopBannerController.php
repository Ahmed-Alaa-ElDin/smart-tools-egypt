<?php

namespace App\Http\Controllers\Admin\Setting\General;

use App\Http\Controllers\Controller;

class TopBannerController extends Controller
{
    public function index()
    {
        return view('admin.setting.general.topbanner.index');
    }
}
