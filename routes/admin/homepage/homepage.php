<?php

use App\Http\Controllers\Admin\HomepageBannerController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\TopSuperCategories;
use Illuminate\Support\Facades\Route;

// HomePage : Start
Route::get('/site/homepage', [HomepageController::class, 'index'])->name('homepage');
Route::get('/site/homepage/create', [HomepageController::class, 'create'])->name('homepage.create');
// HomePage : End

Route::group(['prefix' => '/site', 'as' => 'site.'], function () {
    // Slider : Start
    Route::resource('/homepage/banners', HomepageBannerController::class);
    // Slider : Start

    // Top Super Categories : Start
    Route::get('/homepage/topsupercategories',[TopSuperCategories::class,'index'])->name('topsupercategories.index');
    // Top Super Categories : End
});
