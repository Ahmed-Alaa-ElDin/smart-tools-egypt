<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Section;

class HomepageController extends Controller
{
    public function index()
    {
        // todo :: Make the code more efficient
        $all_products = [];

        ############## Get All Active Sections with Relations :: Start ##############
        $sections = Section::with([
            'products' => fn ($q) => $q->publishedproduct()->withPivot('rank')->orderBy('rank'),
            'offer' => fn ($q) => $q->with([
                'directProducts' => fn ($q) => $q->where('publish', 1),
                'supercategoryProducts' => fn ($q) => $q->where('publish', 1),
                'categoryProducts' => fn ($q) => $q->where('publish', 1),
                'subcategoryProducts' => fn ($q) => $q->where('publish', 1),
                'brandProducts' => fn ($q) => $q->where('publish', 1),
            ]),
            'banners'
        ])->where('active', 1)->orderBy('rank')->get();
        ############## Get All Active Sections with Relations :: End ##############

        ############ Extract of products From Sections :: Start ############
        foreach ($sections as $section) {
            // Section Type is Product List
            if ($section->products->count()) {
                $products_id = $section->products->pluck('id');
                array_push($all_products, ...$products_id);
            }
            // Section Type is Offer
            elseif ($section->offer) {
                $section->offer->directProducts->push(...$section->offer->supercategoryProducts, ...$section->offer->categoryProducts, ...$section->offer->subcategoryProducts, ...$section->offer->brandProducts);

                $section->offer->uniqueProducts = $section->offer->directProducts->unique('id');

                if ($section->offer->uniqueProducts->count() > 11) {
                    $section->offer->uniqueProducts = $section->offer->uniqueProducts->random(11)->shuffle();
                } else {
                    $section->offer->uniqueProducts = $section->offer->uniqueProducts->shuffle();
                }

                $products_id = $section->offer->uniqueProducts->pluck('id');

                array_push($all_products, ...$products_id);
            }
        }
        ############ Extract of products From Sections :: End ############

        ############ Get Best Offer for all products :: Start ############
        $products = getBestOfferForProducts($all_products);
        ############ Get Best Offer for all products :: End ############

        ############ Return Products' Details to Sections :: Start ############
        foreach ($sections as $section) {
            // Section Type is Product List
            if ($section->products->count()) {
                $products_id = $section->products->pluck('id');

                $section->finalProducts = $products->whereIn('id', $products_id)->sortBy(function ($product) use ($products_id) {
                    return array_search($product->id, $products_id->toArray());
                });
            }

            // Section Type is Offer
            elseif ($section->offer) {
                $section->offer->finalProducts = $products->whereIn('id', $section->offer->uniqueProducts->pluck('id'));
            }
        }
        ############ Return Products' Details to Sections :: End ############

        ############ Get All Sections :: Start ############
        $homepage_sections = $sections->where("today_deals", 0);
        ############ Get All Sections :: End ############

        ############ Get Today Deals Section :: Start ############
        $today_deals_sections = $sections->where("today_deals", 1)->first();
        ############ Get Today Deals Section :: End ############

        ############ Get Top Categories :: Start ############
        $categories = Category::where("top", '>', 0)->orderBy("top")->get();
        ############ Get Top Categories :: End ############

        ############ Get Top Brands :: Start ############
        $brands = Brand::where("top", '>', 0)->orderBy("top")->get();
        ############ Get Top Brands :: End ############

        return view('front.homepage.homepage', compact('homepage_sections', 'today_deals_sections', 'categories', 'brands'));
    }
}
