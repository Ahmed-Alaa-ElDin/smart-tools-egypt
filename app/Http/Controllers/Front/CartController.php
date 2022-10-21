<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    ############### Cart View :: Start ##############
    public function index()
    {
        $cart_products_id = [];
        $wishlist_products_id = [];
        $products_id = [];
        $cart_collections_id = [];
        $wishlist_collections_id = [];
        $collections_id = [];

        // get items id from cart
        Cart::instance('cart')->content()->map(function ($item) use (&$products_id, &$collections_id, &$cart_products_id, &$cart_collections_id) {
            if ($item->options->type == 'Product') {
                $products_id[] = $item->id;
                $cart_products_id[] = $item->id;
            } elseif ($item->options->type == 'Collection') {
                $collections_id[] = $item->id;
                $cart_collections_id[] = $item->id;
            }
        });

        // get items id from wishlist
        Cart::instance('wishlist')->content()->map(function ($item) use (&$products_id, &$collections_id, &$wishlist_products_id, &$wishlist_collections_id) {
            if ($item->options->type == 'Product') {
                $products_id[] = $item->id;
                $wishlist_products_id[] = $item->id;
            } elseif ($item->options->type == 'Collection') {
                $collections_id[] = $item->id;
                $wishlist_collections_id[] = $item->id;
            }
        });

        // get items id from cart and wishlist
        $products_id = array_unique($products_id);
        $collections_id = array_unique($collections_id);

        // get all items data from database with best price
        $products = getBestOfferForProducts($products_id);
        $collections = getBestOfferForCollections($collections_id);

        // put items data in cart_items variable
        $cart_products = $products->whereIn('id', $cart_products_id);
        $cart_collections = $collections->whereIn('id', $cart_collections_id);
        $cart_items = $cart_collections->concat($cart_products)->toArray();

        // put items data in wishlist_items variable
        $wishlist_products = $products->whereIn('id', $wishlist_products_id);
        $wishlist_collections = $collections->whereIn('id', $wishlist_collections_id);
        $wishlist_items = $wishlist_collections->concat($wishlist_products)->toArray();

        return view('front.cart.index', compact('cart_items', 'wishlist_items'));
    }
    ############### Cart View :: End ##############
}
