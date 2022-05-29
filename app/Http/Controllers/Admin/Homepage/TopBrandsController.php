<?php

namespace App\Http\Controllers\Admin\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopBrandsController extends Controller
{
    public function index()
    {
        return view('admin.homepage.topbrands.index');
    }
}
