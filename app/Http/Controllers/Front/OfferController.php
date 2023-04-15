<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offer::paginate(config('constants.constants.FRONT_PAGINATION'));

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
        $items = $collections->concat($products)->paginate(config('constants.constants.FRONT_PAGINATION'));
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
}
