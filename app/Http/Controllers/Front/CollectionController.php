<?php

namespace App\Http\Controllers\Front;

use App\Facades\MetaPixel;
use App\Models\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;

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
     */
    public function show($id, $slug = null)
    {
        // Get the collection
        $collection = Collection::with([
            'images' => fn($q) => $q->where('is_thumbnail', 0)->orderBy('featured', 'desc'),
            'products' => fn($q) => $q->select([
                'products.id',
                'name',
                'slug',
                'base_price',
                'final_price',
                'products.quantity',
                'brand_id',
                'under_reviewing'
            ])->with([
                'brand',
                'thumbnail'
            ]),
            'reviews' => fn($q) => $q->with('user'),
            'relatedProducts' => fn($q) => $q->select('products.id'),
            'relatedCollections' => fn($q) => $q->select('collections.id'),
            'complementedProducts' => fn($q) => $q->select('products.id'),
            'complementedCollections' => fn($q) => $q->select('collections.id'),
        ])
            ->where('publish', 1)
            ->findOrFail($id);

        // Get all products ids
        $relatedProductsIds = $collection->relatedProducts->pluck('id')->toArray();
        $complementedProductsIds = $collection->complementedProducts->pluck('id')->toArray();

        $allProductsIds = array_merge($relatedProductsIds, $complementedProductsIds);

        // Get all collections ids
        $collectionId = $collection->id;
        $relatedCollectionsIds = $collection->relatedCollections->pluck('id')->toArray();
        $complementedCollectionsIds = $collection->complementedCollections->pluck('id')->toArray();

        $allCollectionsIds = array_merge([$collectionId], $relatedCollectionsIds, $complementedCollectionsIds);

        // Get the product's Best Offer for all products
        $productsOffers = getBestOfferForProducts($allProductsIds);

        // Get the product's Best Offer for all collections
        $collectionsOffers = getBestOfferForCollections($allCollectionsIds);

        // Get the collection's Best Offer
        $collectionOffer = $collectionsOffers->where('id', $collectionId)->first();

        // Get the product's related products and collections
        $relatedProducts = $productsOffers->whereIn('id', $relatedProductsIds);
        $relatedCollections = $collectionsOffers->whereIn('id', $relatedCollectionsIds);
        $relatedItems = $relatedProducts->concat($relatedCollections)->toArray();

        // Get the product's complemented products and collections
        $complementedProducts = $productsOffers->whereIn('id', $complementedProductsIds);
        $complementedCollections = $collectionsOffers->whereIn('id', $complementedCollectionsIds);
        $complementedItems = $complementedProducts->concat($complementedCollections)->toArray();

        // Get the collection's data from the cart
        $collectionCart = Cart::instance('cart')->search(function ($cartItem) use ($id) {
            return $cartItem->id == $id;
        });

        // Get the app locale
        $locale = session('locale');

        $collectionProducts = $collection->products->map(fn($product) => [
            'id' => $product->id,
            'item_price' => $product->final_price,
        ])->toArray();

        $collectionProductsIds = $collection->products->pluck('id')->toArray();

        // Send Meta Pixel event
        MetaPixel::sendEvent('ViewContent', [], [
            'content_type' => 'product_group',
            'content_ids' => [$collection->id, ...$collectionProductsIds],
            'content_name' => $collection->name,
            'contents' => $collectionProducts,
            'currency' => 'EGP',
            'value' => $collection->final_price,
        ]);

        return view('front.collection_page.collection_page', compact('collection', 'collectionOffer', 'relatedItems', 'complementedItems', 'collectionCart', 'locale'));
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
