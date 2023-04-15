<?php

namespace App\Http\Controllers\Admin\Setting\Homepage;

use App\Http\Controllers\Controller;

class TopSuperCategoriesController extends Controller
{
    public function index()
    {
        return view('admin.setting.homepage.topsupercategories.index');
    }
}
