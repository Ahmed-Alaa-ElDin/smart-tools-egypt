<?php

namespace App\Http\Controllers\Admin\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopCategories extends Controller
{
    public function index()
    {
        return view('admin.homepage.topcategories.index');
    }
}
