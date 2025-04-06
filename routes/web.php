<?php

use App\Models\Order;
use Livewire\Livewire;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {
    Livewire::setUpdateRoute(function ($handle) {
        return Route::post('/livewire/update', $handle);
    });

    Route::get('/po', function () {
        $order = Order::select([
            'orders.id',
            'user_id',
            'address_id',
            'phone1',
            'phone2',
            'num_of_items',
            'zone_id',
            'created_at',
        ])->with([
            'user' => function ($query) {
                $query->select('users.id', 'f_name', 'l_name')
                    ->without('addresses', 'phones', 'points');
            },
            'address' => function ($query) {
                $query
                    ->select('addresses.id', 'governorate_id', 'city_id', 'details', 'landmarks')
                    ->with([
                        'governorate' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'city' => function ($query) {
                            $query->select('id', 'name');
                        },
                    ]);
            },
            'invoice',
            'products' => function ($query) {
                $query->select('products.id', 'name', 'base_price', 'final_price', 'model')
                    ->without('orders', 'brand', 'reviews', 'valid_offers', 'avg_rating');
            },
            'collections' => function ($query) {
                $query->select('collections.id', 'collections.name', 'base_price', 'final_price')
                    ->with(['products' => function ($query) {
                        $query->select('products.id', 'name', 'base_price', 'final_price', 'model')
                            ->without('orders', 'brand', 'reviews', 'valid_offers', 'avg_rating');
                    }])
                    ->without('orders', 'brand', 'reviews', 'valid_offers', 'avg_rating');
            }
        ])->latest()->first()->toArray();

        $order['items'] = array_merge($order['products'], $order['collections']);

        $order['subtotal'] = $order['invoice']['subtotal_base'];
        $order['discount'] = $order['invoice']['items_discount'] + $order['invoice']['offers_items_discount'] + $order['invoice']['coupon_items_discount'];
        $order['extra_discount'] = $order['invoice']['offers_order_discount'] + $order['invoice']['coupon_order_discount'];
        $order['delivery_fees'] = $order['invoice']['delivery_fees'];
        $order['total'] = $order['invoice']['total'];

        return view('front.orders.purchase-order', compact('order'));
    });

    // Admin Authentication
    require_once __DIR__ . "/admin/auth.php";
    // Other Admin Routes
    require_once __DIR__ . "/admin/admin.php";

    // Users Authentications
    require_once __DIR__ . "/front/auth.php";
    // Other Users Routes
    require_once __DIR__ . "/front/front.php";
});

// require __DIR__.'/auth.php';
