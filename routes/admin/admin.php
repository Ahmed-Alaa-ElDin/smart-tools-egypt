<?php


use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'as' =>   'admin.',
    'prefix' => '/admin',
], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ############## Orders Routes Start ##############
    require_once __DIR__ . "/orders/orders.php";
    // ############## Orders Routes End ##############

    // ############## Products Routes Start ##############
    require_once __DIR__ . "/products_brands/products_brands.php";
    // ############## Products Routes End ##############

    // ############## Offers Routes Start ##############
    require_once __DIR__ . "/offers/offers.php";
    // ############## Offers Routes End ##############

    // ############## Categories Routes Start ##############
    require_once __DIR__ . "/categories/categories.php";
    // ############## Categories Routes End ##############

    // ############## Users Routes Start ##############
    require_once __DIR__ . "/users_roles/users_roles.php";
    // ############## Users Routes End ##############

    // ############## Delivery System Routes Start ##############
    require_once __DIR__ . "/deliveries_addresses/deliveries_addresses.php";
    // ############## Delivery System Routes End ##############

    // ############## Home Page Control Start ##############
    require_once __DIR__ . "/homepage/homepage.php";
    // ############## Home Page Control End ##############
});
