<?php

namespace App\Http\Controllers\Admin\Homepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomepageController extends Controller
{

    public function index()
    {
        return view('admin.homepage.index');
    }

    public function create()
    {
        return view('admin.homepage.sections.create');
    }

    public function edit($section_id)
    {
        return view('admin.homepage.sections.edit',compact('section_id'));
    }
}
