<?php

use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\HomepageBannerController;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

// HomePage : Start
Route::get('/site/homepage', [HomepageController::class, 'index'])->name('homepage');
Route::get('/site/homepage/create', [HomepageController::class, 'create'])->name('homepage.create');
// HomePage : End

Route::group(['prefix' => '/site', 'as' => 'site.'], function () {
    // Slider : Start
    Route::resource('/homepage/banners', HomepageBannerController::class);
    // Slider : Start

});
