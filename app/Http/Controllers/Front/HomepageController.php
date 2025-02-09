<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Section;
use Symfony\Component\HttpFoundation\Request;

class HomepageController extends Controller
{
    public function index()
    {
        resizeExistingImages("products");
        resizeExistingImages("collections");

        // todo :: Make the code more efficient
        $all_products = [];
        $all_collections = [];

        ############## Get All Active Sections with Relations :: Start ##############
        $sections = Section::with([
            'products' => fn ($q) => $q
                ->select(['products.id', 'products.publish'])
                ->where('products.publish', 1)
                ->with('brand')
                ->withPivot('rank')
                ->orderBy('rank'),
            'collections' => fn ($q) => $q
                ->select(['collections.id', 'collections.publish'])
                ->where('collections.publish', 1)
                ->withPivot('rank')
                ->orderBy('rank'),
            'offer' => fn ($q) => $q->with([
                'directProducts' => fn ($q) => $q->with('brand')->where('products.publish', 1),
                'directCollections' => fn ($q) => $q->where('collections.publish', 1),
                'supercategoryProducts' => fn ($q) => $q->where('products.publish', 1),
                'categoryProducts' => fn ($q) => $q->where('products.publish', 1),
                'subcategoryProducts' => fn ($q) => $q->where('products.publish', 1),
                'brandProducts' => fn ($q) => $q->where('products.publish', 1),
            ]),
            'banners'
        ])
            ->where('active', 1)
            ->orderBy('rank')->get();
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
        $products = getBestOfferForProducts($all_products)->map(function ($product) {
            $product->type = "Product";
            return $product;
        });
        ############ Get Best Offer for all products :: End ############

        ############ Extract of collections From Sections :: Start ############
        foreach ($sections as $section) {
            // Section Type is Product List
            if ($section->collections->count()) {
                $collections_id = $section->collections->pluck('id');
                array_push($all_collections, ...$collections_id);
            }
            // Section Type is Offer
            elseif ($section->offer) {
                $section->offer->uniqueCollections = $section->offer->directCollections->unique('id');

                if ($section->offer->uniqueCollections->count() > 11) {
                    $section->offer->uniqueCollections = $section->offer->uniqueCollections->random(11)->shuffle();
                } else {
                    $section->offer->uniqueCollections = $section->offer->uniqueCollections->shuffle();
                }

                $collections_id = $section->offer->uniqueCollections->pluck('id');

                array_push($all_collections, ...$collections_id);
            }
        }
        ############ Extract of collections From Sections :: End ############

        ############ Get Best Offer for all collections :: Start ############
        $collections = getBestOfferForCollections($all_collections)->map(function ($collection) {
            $collection->type = "Collection";
            return $collection;
        });
        ############ Get Best Offer for all collections :: End ############

        ############ Concatenation of best Products & Collections  :: Start ############
        $items = $collections->concat($products);
        ############ Concatenation of best Products & Collections  :: End ############

        ############ Return Products' Details to Sections :: Start ############
        foreach ($sections as $section) {
            // Section Type is Product List
            if ($section->products->count() || $section->collections->count()) {
                $products_id = $section->products->pluck('id');
                $collections_id = $section->collections->pluck('id');

                $finalProducts = $items
                    ->whereIn('id', $products_id)
                    ->where('type', "Product")
                    ->sortBy(function ($product) use ($products_id) {
                        return array_search($product->id, $products_id->toArray());
                    });

                $finalCollections = $items
                    ->whereIn('id', $collections_id)
                    ->where('type', "Collection")
                    ->sortBy(function ($collection) use ($collections_id) {
                        return array_search($collection->id, $collections_id->toArray());
                    });

                $section->finalItems = $finalCollections->concat($finalProducts);
            }

            // Section Type is Offer
            elseif ($section->offer) {
                $products_id = $section->offer->uniqueProducts->pluck('id');

                $finalProducts = $items
                    ->whereIn('id', $products_id)
                    ->where('type', "Product")
                    ->sortBy(function ($product) use ($products_id) {
                        return array_search($product->id, $products_id->toArray());
                    });

                $collections_id = $section->offer->uniqueProducts->pluck('id');

                $finalCollections = $items
                    ->whereIn('id', $collections_id)
                    ->where('type', "Collection")
                    ->sortBy(function ($collection) use ($collections_id) {
                        return array_search($collection->id, $collections_id->toArray());
                    });;

                $section->offer->finalItems = $finalCollections->concat($finalProducts);
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
        $categories = Category::select('id', 'name', 'top')->without('validOffers')->with('images')->where("top", '>', 0)->orderBy("top")->get();
        ############ Get Top Categories :: End ############

        ############ Get Top Brands :: Start ############
        $brands = Brand::select('id', 'name', 'top', 'logo_path')->without('validOffers')->where("top", '>', 0)->orderBy("top")->get();
        ############ Get Top Brands :: End ############

        return view('front.homepage.homepage', compact('homepage_sections', 'today_deals_sections', 'categories', 'brands'));
    }

    public function search(Request $request)
    {
        $search = $request->get('search');

        return view('front.search.search_page', compact('search'));
    }

    public function showProductList(int $section_id = 1)
    {
        $section = Section::with([
            'products' => fn ($q) => $q->select('products.id'),
            'collections' => fn ($q) => $q->select('collections.id')
        ])->findOrFail($section_id);

        ############ Get Best Offer for all products :: Start ############
        $products_id = $section->products->pluck('id');

        $products = getBestOfferForProducts($products_id)->map(function ($product) {
            $product->type = "Product";
            return $product;
        });
        ############ Get Best Offer for all products :: End ############

        ############ Get Best Offer for all collections :: Start ############
        $collections_id = $section->collections->pluck('id');

        $collections = getBestOfferForCollections($collections_id)->map(function ($collection) {
            $collection->type = "Collection";
            return $collection;
        });
        ############ Get Best Offer for all collections :: End ############

        ############ Concatenation of best Products & Collections  :: Start ############
        $items = $collections->concat($products)->paginate(config('settings.front_pagination'));
        ############ Concatenation of best Products & Collections  :: End ############

        return view('front.sections.section_products', compact('section', 'items'));
    }
}
