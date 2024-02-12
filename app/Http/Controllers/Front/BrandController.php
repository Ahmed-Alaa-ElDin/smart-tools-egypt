<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::withCount('products')->get();

        return view('front.brands.index', compact('brands'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show($brand_id)
    {
        $brand = Brand::with([
            'products' => fn ($q) => $q->select('id', 'brand_id'),
        ])->without('validOffers')->findOrFail($brand_id);

        $productsIds = $brand->products->pluck('id');

        $products = getBestOfferForProducts($productsIds)->paginate(config('settings.front_pagination'));

        return view('front.brands.show', compact(['brand', 'products']));
    }
}
