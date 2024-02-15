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
            'relatedProducts' => fn ($q) => $q->select('products.id'),
            'relatedCollections' => fn ($q) => $q->select('collections.id'),
            'complementedProducts' => fn ($q) => $q->select('products.id'),
            'complementedCollections' => fn ($q) => $q->select('collections.id'),
        ])->findOrFail($id);

        // Get all products ids
        $productId = $product->id;
        $relatedProductsIds = $product->relatedProducts->pluck('id')->toArray();
        $complementedProductsIds = $product->complementedProducts->pluck('id')->toArray();

        $allProductsIds = array_merge([$productId], $relatedProductsIds, $complementedProductsIds);

        // Get all collections ids
        $relatedCollectionsIds = $product->relatedCollections->pluck('id')->toArray();
        $complementedCollectionsIds = $product->complementedCollections->pluck('id')->toArray();

        $allCollectionsIds = array_merge($relatedCollectionsIds, $complementedCollectionsIds);

        // Get the product's Best Offer for all products
        $productsOffers = getBestOfferForProducts($allProductsIds, false);

        // Get the product's Best Offer for all collections
        $collectionsOffers = getBestOfferForCollections($allCollectionsIds);

        // Get related/complementary products/collections id (key) and rank (value)
        $relatedProductsRank = $product->relatedProducts->pluck('pivot.rank', 'id')->toArray();
        $complementedProductsRank = $product->complementedProducts->pluck('pivot.rank', 'id')->toArray();
        $relatedCollectionsRank = $product->relatedCollections->pluck('pivot.rank', 'id')->toArray();
        $complementedCollectionsRank = $product->complementedCollections->pluck('pivot.rank', 'id')->toArray();

        // Main Product Offer
        $productOffer = $productsOffers->where('id', $productId)->first();

        // Get the product's related products and collections
        $relatedProducts = $productsOffers->whereIn('id', $relatedProductsIds)->map(function ($product) use ($relatedProductsRank) {
            $product->rank = $relatedProductsRank[$product->id];
            return $product;
        });
        $relatedCollections = $collectionsOffers->whereIn('id', $relatedCollectionsIds)->map(function ($collection) use ($relatedCollectionsRank) {
            $collection->rank = $relatedCollectionsRank[$collection->id];
            return $collection;
        });
        $relatedItems = $relatedProducts->concat($relatedCollections)
            ->sortBy('rank')
            ->toArray();

        // Get the product's complemented products and collections
        $complementedProducts = $productsOffers->whereIn('id', $complementedProductsIds)->map(function ($product) use ($complementedProductsRank) {
            $product->rank = $complementedProductsRank[$product->id];
            return $product;
        });
        $complementedCollections = $collectionsOffers->whereIn('id', $complementedCollectionsIds)->map(function ($collection) use ($complementedCollectionsRank) {
            $collection->rank = $complementedCollectionsRank[$collection->id];
            return $collection;
        });
        $complementedItems = $complementedProducts->concat($complementedCollections)
            ->sortBy('rank')
            ->toArray();

        // Get the product's data from the cart
        $product_cart = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id == $id;
        });

        // Get the app locale
        $locale = session('locale');

        return view('front.product_page.product_page', compact('product', 'productOffer', 'relatedItems', 'complementedItems', 'product_cart', 'locale'));
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
