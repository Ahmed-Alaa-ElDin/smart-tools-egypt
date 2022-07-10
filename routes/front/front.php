<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\HomepageController;
use App\Http\Controllers\Front\ProductController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['getCart'],
    'as' =>   'front.',
    'prefix' => '',
], function () {
    Route::get('/', [HomepageController::class, 'index'])->name('homepage');

    ################ Product Controller :: Start ##############
    Route::get('/product/{id}/{slug}', [ProductController::class, 'show'])->name('product.show');
    ################ Product Controller :: End ##############

    ################ Cart Controller :: Start ##############
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    ################ Cart Controller :: End ##############
});
