<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function shipping()
    {
        $products_id = [];

        // get products id from cart
        $cart_products_id = Cart::instance('cart')->content()->pluck('id')->toArray();

        // get products id from wishlist
        $wishlist_products_id = Cart::instance('wishlist')->content()->pluck('id')->toArray();

        // get products id from cart and wishlist
        $products_id = array_unique(array_merge($cart_products_id, $wishlist_products_id));

        $products = [];

        // get all products data from database with best price
        $products = getBestOfferForProducts($products_id);

        // put products data in cart_products variable
        $cart_products = $products->whereIn('id', $cart_products_id);

        // put products data in wishlist_products variable
        $wishlist_products = $products->whereIn('id', $wishlist_products_id);

        return view('front.cart.shipping', compact('cart_products', 'wishlist_products'));
    }

    public function billing()
    {
        return view('front.cart.billing');
    }

    public function done()
    {
        return view('front.cart.done');
    }
}
