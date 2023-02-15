<?php

namespace App\Http\Controllers\Admin\Homepage;

use App\Http\Controllers\Controller;

class TopSubcategoriesController extends Controller
{
    public function index()
    {
        return view('admin.homepage.topsubcategories.index');
    }
}
