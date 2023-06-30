<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class ComparisonController extends Controller
{
    public function index()
    {
        return view('front.comparison.index');
    }
}
