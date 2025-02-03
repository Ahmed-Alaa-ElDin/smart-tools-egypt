<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $subcategories = Subcategory::without('validOffers')
            ->withCount(['products'])
            ->orderBy('products_count', 'desc')
            ->paginate(config('settings.front_pagination'));

        return view('front.subcategories.index', compact('subcategories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subcategory  $subcategory
     */
    public function show($subcategory_id)
    {
        $subcategory = Subcategory::without('validOffers')
            ->findOrFail($subcategory_id);

        return view('front.subcategories.show', compact(['subcategory']));
    }
}
