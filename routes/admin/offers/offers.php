<?php

use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OfferController;
use Illuminate\Support\Facades\Route;

Route::get('/offers/deleted-offers', [OfferController::class, 'softDeletedOffers'])->name('offers.softDeletedOffers');
Route::resource('/offers', OfferController::class);

Route::resource('/coupons', CouponController::class);
