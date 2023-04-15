<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subcategories = Subcategory::without('validOffers')
            ->withCount(['products'])
            ->orderBy('products_count', 'desc')
            ->paginate(config('constants.constants.FRONT_PAGINATION'));

        return view('front.subcategories.index', compact('subcategories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function show($subcategory_id)
    {
        $subcategory = Subcategory::with([
            'products' => fn ($q) => $q->select('products.id'),
        ])
            ->without('validOffers')
            ->findOrFail($subcategory_id);

        $productsIds = $subcategory->products->pluck('id');

        $products = getBestOfferForProducts($productsIds)->paginate(config('constants.constants.FRONT_PAGINATION'));

        return view('front.subcategories.show', compact(['subcategory', 'products']));
    }
}
