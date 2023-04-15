<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::without('validOffers')
            ->with('images')
            ->withCount(['products', 'subcategories'])
            ->orderBy('products_count', 'desc')
            ->paginate(config('constants.constants.FRONT_PAGINATION'));

        return view('front.categories.index', compact('categories'));
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($category_id)
    {
        $category = Category::withOut('validOffers')
            ->with(['subcategories' => function ($q) {
                $q->withOut('validOffers')
                    ->orderBy('products_count', 'desc')
                    ->withCount(['products']);
            }])
            ->findOrFail($category_id);

        $subcategories = $category->subcategories->paginate(config('constants.constants.FRONT_PAGINATION'));

        return view('front.categories.show', compact('category', 'subcategories'));
    }

    public function products($category_id)
    {
        $category = Category::withOut('validOffers')
            ->with([
                'products' => fn ($q) => $q->select('products.id'),
            ])
            ->findOrFail($category_id);

        $productsIds = $category->products->pluck('id');

        $products = getBestOfferForProducts($productsIds)->paginate(config('constants.constants.FRONT_PAGINATION'));

        return view('front.categories.products', compact('category', 'products'));
    }
}
