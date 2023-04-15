<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function show($id, $slug = null)
    {
        // Get the collection
        $collection = Collection::with([
            'images' => fn ($q) => $q->where('is_thumbnail', 0)->orderBy('featured', 'desc'),
            'products' => fn ($q) => $q->select([
                'products.id', 'name', 'slug', 'base_price', 'final_price', 'products.quantity', 'brand_id', 'under_reviewing'
            ])->with([
                'brand', 'thumbnail'
            ]),
            'reviews' => fn ($q) => $q->with('user')
        ])->findOrFail($id);

        // Get the collection's Best Offer
        $collection_offer = getBestOfferForCollection($id);

        // Get the collection's data from the cart
        $collection_cart = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id == $id;
        });

        // Get the app locale
        $locale = session('locale');

        return view('front.collection_page.collection_page', compact('collection', 'collection_offer', 'collection_cart', 'locale'));
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
