<?php

namespace App\Http\Controllers\Admin\Homepage;

use App\Http\Controllers\Controller;

class TopSuperCategoriesController extends Controller
{
    public function index()
    {
        return view('admin.homepage.topsupercategories.index');
    }
}
