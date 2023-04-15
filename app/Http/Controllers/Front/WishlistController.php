<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $products_id = [];
        // $collections_id = [];

        // get items id from wishlist
        // Cart::instance('wishlist')->content()->map(function ($item) use (&$products_id, &$collections_id) {
        //     if ($item->options->type == 'Product') {
        //         $products_id[] = $item->id;
        //         $wishlist_products_id[] = $item->id;
        //     } elseif ($item->options->type == 'Collection') {
        //         $collections_id[] = $item->id;
        //         $wishlist_collections_id[] = $item->id;
        //     }
        // });

        // get all items data from database with best price
        // $products = getBestOfferForProducts($products_id);
        // $collections = getBestOfferForCollections($collections_id);

        // $items = $collections->concat($products);

        return view('front.wishlist.index');
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
        //
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
