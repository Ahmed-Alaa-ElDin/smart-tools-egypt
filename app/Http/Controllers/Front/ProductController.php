<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class ProductController extends Controller
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
        // Get the product
        $product = Product::with([
            'specs',
            'images' => fn ($q) => $q->where('is_thumbnail', 0)->orderBy('featured', 'desc'),
            'brand',
            'reviews' => fn ($q) => $q->with('user'),
            'relatableProducts' => fn ($q) => $q->select('products.id'),
            'relatableCollections' => fn ($q) => $q->select('collections.id'),
            'complementableProducts' => fn ($q) => $q->select('products.id'),
            'complementableCollections' => fn ($q) => $q->select('collections.id'),
        ])->findOrFail($id);

        // Get all products ids
        $productId = $product->id;
        $relatableProductsIds = $product->relatableProducts->pluck('id')->toArray();
        $complementableProductsIds = $product->complementableProducts->pluck('id')->toArray();

        $allProductsIds = array_merge([$productId], $relatableProductsIds, $complementableProductsIds);

        // Get all collections ids
        $relatableCollectionsIds = $product->relatableCollections->pluck('id')->toArray();
        $complementableCollectionsIds = $product->complementableCollections->pluck('id')->toArray();

        $allCollectionsIds = array_merge($relatableCollectionsIds, $complementableCollectionsIds);

        // Get the product's Best Offer for all products
        $productsOffers = getBestOfferForProducts($allProductsIds);

        // Get the product's Best Offer for all collections
        $collectionsOffers = getBestOfferForCollections($allCollectionsIds);

        // Main Product Offer
        $productOffer = $productsOffers->where('id', $productId)->first();

        // Get the product's related products and collections
        $relatedProducts = $productsOffers->whereIn('id', $relatableProductsIds);
        $relatedCollections = $collectionsOffers->whereIn('id', $relatableCollectionsIds);
        $relatedItems = $relatedProducts->concat($relatedCollections)->toArray();

        // Get the product's complementable products and collections
        $complementableProducts = $productsOffers->whereIn('id', $complementableProductsIds);
        $complementableCollections = $collectionsOffers->whereIn('id', $complementableCollectionsIds);
        $complementableItems = $complementableProducts->concat($complementableCollections)->toArray();

        // Get the product's data from the cart
        $product_cart = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id == $id;
        });

        // Get the app locale
        $locale = session('locale');

        return view('front.product_page.product_page', compact('product', 'productOffer', 'relatedItems', 'complementableItems', 'product_cart', 'locale'));
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
