<?php

namespace App\Http\Controllers\Front;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::without('validOffers')
            ->with('images')
            ->withCount(['products' => function ($query) {
                $query->select(DB::raw('count(distinct products.id)'));
            }])
            ->withCount(['subcategories'])
            ->orderBy('products_count', 'desc')
            ->paginate(config('settings.front_pagination'));

        return view('front.categories.index', compact('categories'));
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     */
    public function show($category_id)
    {
        $category = Category::withOut('validOffers')
            ->with(['subcategories' => function ($q) {
                $q->withOut('validOffers')
                    ->orderBy('products_count', 'desc')
                    ->withCount(['products' => function ($query) {
                        $query->select(DB::raw('count(distinct products.id)'));
                    }]);
            }])
            ->findOrFail($category_id);

        $subcategories = $category->subcategories->paginate(config('settings.front_pagination'));

        return view('front.categories.show', compact('category', 'subcategories'));
    }

    public function products($category_id)
    {
        $category = Category::withOut('validOffers')
            ->findOrFail($category_id);

        return view('front.categories.products', compact('category'));
    }
}
