<?php

namespace App\Http\Controllers\Front;

use App\Models\Supercategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SupercategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supercategories = Supercategory::without('validOffers')
            ->withCount(['products' => function ($query) {
                $query->select(DB::raw('count(distinct products.id)'));
            }])
            ->withCount(['categories', 'subcategories'])
            ->orderBy('products_count', 'desc')
            ->paginate(config('settings.front_pagination'));

        return view('front.supercategories.index', compact('supercategories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supercategory  $supercategory
     */
    public function show($supercategory_id)
    {
        $supercategory = Supercategory::withOut('validOffers')
            ->with(['categories' => function ($q) {
                $q->with('images')->withOut('validOffers')
                    ->orderBy('products_count', 'desc')
                    ->withCount(['products' => function ($query) {
                        $query->select(DB::raw('count(distinct products.id)'));
                    }])
                    ->withCount(['subcategories']);
            }])
            ->findOrFail($supercategory_id);

        $categories = $supercategory->categories->paginate(config('settings.front_pagination'));

        return view('front.supercategories.show', compact('supercategory', 'categories'));
    }

    public function subcategories($supercategory_id)
    {
        $supercategory = Supercategory::withOut('validOffers')
            ->with(['subcategories' => function ($q) {
                $q->withOut('validOffers')
                ->withCount(['products' => function ($query) {
                    $query->select(DB::raw('count(distinct products.id)'));
                }])
                ->orderBy('products_count', 'desc');
            }])
            ->findOrFail($supercategory_id);

        $subcategories = $supercategory->subcategories->paginate(config('settings.front_pagination'));

        return view('front.supercategories.subcategories', compact('supercategory', 'subcategories'));
    }

    public function products($supercategory_id)
    {
        $supercategory = Supercategory::withOut('validOffers')
            ->findOrFail($supercategory_id);

        return view('front.supercategories.products', compact('supercategory'));
    }
}
