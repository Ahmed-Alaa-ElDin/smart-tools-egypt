<?php

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
