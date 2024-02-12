<?php

namespace App\Http\Controllers\Front;

use App\Models\Offer;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offer::paginate(config('settings.front_pagination'));

        return view('front.offers.index', compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $offer = Offer::with(
            [
                'directProducts' => fn ($q) => $q->where('products.publish', 1),
                'directCollections' => fn ($q) => $q->where('collections.publish', 1),
                'supercategoryProducts' => fn ($q) => $q->where('products.publish', 1),
                'categoryProducts' => fn ($q) => $q->where('products.publish', 1),
                'subcategoryProducts' => fn ($q) => $q->where('products.publish', 1),
                'brandProducts' => fn ($q) => $q->where('products.publish', 1),
            ]
        )->findOrFail($id);

        ############ Extract of products & Collection From Offers :: Start ############
        $offer->directProducts->push(...$offer->supercategoryProducts, ...$offer->categoryProducts, ...$offer->subcategoryProducts, ...$offer->brandProducts);
        $uniqueProducts = $offer->directProducts->unique('id')->pluck('id');

        $uniqueCollections = $offer->directCollections->pluck('id');
        ############ Extract of products & Collection From Offers :: End ############

        ############ Get Best Offer for all products :: Start ############
        $products = getBestOfferForProducts($uniqueProducts)->map(function ($product) {
            $product->type = "Product";
            return $product;
        });
        ############ Get Best Offer for all products :: End ############

        ############ Get Best Offer for all collections :: Start ############
        $collections = getBestOfferForCollections($uniqueCollections)->map(function ($collection) {
            $collection->type = "Collection";
            return $collection;
        });
        ############ Get Best Offer for all collections :: End ############

        ############ Concatenation of best Products & Collections  :: Start ############
        $items = $collections->concat($products)->paginate(config('settings.front_pagination'));
        ############ Concatenation of best Products & Collections  :: End ############

        return view('front.offers.show', compact('offer', 'items'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Last Box Offer Page
     */
    public function lastBox()
    {
        $settings = Setting::first();

        $offer = new Offer;
        $offer->id = 0;
        $offer->title = $settings->getTranslations("last_box_name");
        $offer->banner = null;
        $offer->free_shipping = 0;
        $offer->start_at = null;
        $offer->expire_at = null;
        $offer->value = 0;
        $offer->type = 0;
        $offer->on_orders = 0;
        $offer->created_at = $settings->created_at;
        $offer->updated_at = $settings->updated_at;

        $productsIds = Product::where('publish', 1)->where('quantity', '>', 0)->where('quantity', '<=', $settings->last_box_quantity)->pluck('id');

        $items = getBestOfferForProducts($productsIds)->paginate(config('settings.front_pagination'));

        return view('front.offers.show', compact('offer', 'items'));
    }

    /**
     * New Arrival Offer Page
     */
    public function newArrival()
    {
        $settings = Setting::first();

        $offer = new Offer;
        $offer->id = 0;
        $offer->title = $settings->getTranslations("new_arrival_name");
        $offer->banner = null;
        $offer->free_shipping = 0;
        $offer->start_at = null;
        $offer->expire_at = null;
        $offer->value = 0;
        $offer->type = 0;
        $offer->on_orders = 0;
        $offer->created_at = $settings->created_at;
        $offer->updated_at = $settings->updated_at;

        // get products that created between today and $settings->new_arrival_period days ago
        $startDate = now()->subDays($settings->new_arrival_period)->startOfDay();
        $endDate = now();

        $productsIds = Product::where('publish', 1)->where('quantity', '>', 0)->whereBetween("created_at", [$startDate, $endDate])->pluck('id');

        $items = getBestOfferForProducts($productsIds)->paginate(config('settings.front_pagination'));

        return view('front.offers.show', compact('offer', 'items'));
    }

    /**
     * Maximum Price Offer
     */
    public function maxPrice(){
        $settings = Setting::first();

        $offer = new Offer;
        $offer->id = 0;
        $offer->title = $settings->getTranslations("max_price_offer_name");
        $offer->banner = null;
        $offer->free_shipping = 0;
        $offer->start_at = null;
        $offer->expire_at = null;
        $offer->value = 0;
        $offer->type = 0;
        $offer->on_orders = 0;
        $offer->created_at = $settings->created_at;
        $offer->updated_at = $settings->updated_at;

        $productsIds = Product::where('publish', 1)->where('quantity', '>', 0)->get()->pluck('id');

        $items = getBestOfferForProducts($productsIds)->where("best_price", "<=", $settings->max_price_offer)->paginate(config('settings.front_pagination'));

        return view('front.offers.show', compact('offer', 'items'));
    }
}
