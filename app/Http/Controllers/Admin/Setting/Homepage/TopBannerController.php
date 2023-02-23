<?php

namespace App\Http\Controllers\Admin\Setting\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopBannerController extends Controller
{
    public function index()
    {
        return view('admin.setting.homepage.topbanner.index');
    }
}
