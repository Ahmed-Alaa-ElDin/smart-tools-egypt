<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Supercategory;
use Illuminate\Http\Request;

class SupercategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supercategories = Supercategory::without('validOffers')
            ->withCount(['products', 'categories', 'subcategories'])
            ->orderBy('products_count', 'desc')
            ->paginate(config('constants.constants.FRONT_PAGINATION'));

        return view('front.supercategories.index', compact('supercategories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supercategory  $supercategory
     * @return \Illuminate\Http\Response
     */
    public function show($supercategory_id)
    {
        $supercategory = Supercategory::withOut('validOffers')
            ->with(['categories' => function ($q) {
                $q->with('images')->withOut('validOffers')
                    ->orderBy('products_count', 'desc')
                    ->withCount(['subcategories', 'products']);
            }])
            ->findOrFail($supercategory_id);

        $categories = $supercategory->categories->paginate(config('constants.constants.FRONT_PAGINATION'));

        return view('front.supercategories.show', compact('supercategory', 'categories'));
    }

    public function subcategories($supercategory_id)
    {
        $supercategory = Supercategory::withOut('validOffers')
            ->with(['subcategories' => function ($q) {
                $q->withOut('validOffers')
                    ->withCount(['products'])
                    ->orderBy('products_count', 'desc');
            }])
            ->findOrFail($supercategory_id);

        $subcategories = $supercategory->subcategories->paginate(config('constants.constants.FRONT_PAGINATION'));

        return view('front.supercategories.subcategories', compact('supercategory', 'subcategories'));
    }

    public function products($supercategory_id)
    {
        $supercategory = Supercategory::withOut('validOffers')
            ->with([
                'products' => fn ($q) => $q->select('products.id'),
            ])
            ->findOrFail($supercategory_id);

        $productsIds = $supercategory->products->pluck('id');

        $products = getBestOfferForProducts($productsIds)->paginate(config('constants.constants.FRONT_PAGINATION'));

        return view('front.supercategories.products', compact('supercategory', 'products'));
    }
}
