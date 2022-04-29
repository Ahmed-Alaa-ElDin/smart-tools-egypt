<?php

namespace App\Http\Controllers\Admin\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopSuperCategories extends Controller
{
    public function index()
    {
        return view('admin.homepage.topsupercategories.index');
    }
}
