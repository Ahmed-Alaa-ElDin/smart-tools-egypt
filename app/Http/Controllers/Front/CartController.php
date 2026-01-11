<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Traits\Front\EnrichesCartItems;

class CartController extends Controller
{
    use EnrichesCartItems;

    /**
     * Display the shopping cart and wishlist items.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cart_items = $this->getEnrichedItems('cart');
        $wishlist_items = $this->getEnrichedItems('wishlist');

        return view('front.cart.index', compact('cart_items', 'wishlist_items'));
    }
}