<?php

namespace App\Http\Controllers\Admin\Setting\Homepage;

use App\Http\Controllers\Controller;

class HomepageController extends Controller
{

    public function index()
    {
        return view('admin.setting.homepage.index');
    }

    public function create()
    {
        return view('admin.setting.homepage.sections.create');
    }

    public function edit($section_id)
    {
        return view('admin.setting.homepage.sections.edit',compact('section_id'));
    }
}
