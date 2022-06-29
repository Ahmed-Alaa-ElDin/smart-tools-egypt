<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    ############### Cart View :: Start ##############
    public function index()
    {
        $products_id = [];

        // get products id from cart
        $cart_products_id = Cart::instance('cart')->content()->pluck('id')->toArray();

        // get products id from wishlist
        $wishlist_products_id = Cart::instance('wishlist')->content()->pluck('id')->toArray();

        // get products id from cart and wishlist
        $products_id = array_unique(array_merge($cart_products_id, $wishlist_products_id));

        $products = [];

        // get all products data from database
        foreach ($products_id as $product_id) {
            $products[] = getBestOffer($product_id)->toArray();
        }

        // put products data in cart_products variable
        $cart_products = array_filter($products, function ($product) use ($cart_products_id) {
            return in_array($product['id'], $cart_products_id);
        });

        // put products data in wishlist_products variable
        $wishlist_products = array_filter($products, function ($product) use ($wishlist_products_id) {
            return in_array($product['id'], $wishlist_products_id);
        });

        return view('front.cart.cart', compact('cart_products', 'wishlist_products'));
    }
    ############### Cart View :: End ##############
}
