<?php

use App\Http\Controllers\Admin\Homepage\BannerController;
use App\Http\Controllers\Admin\Homepage\HomepageController;
use App\Http\Controllers\Admin\Homepage\TodayDealsController;
use App\Http\Controllers\Admin\Homepage\TopCategories;
use App\Http\Controllers\Admin\Homepage\TopSuperCategories;
use Illuminate\Support\Facades\Route;

// HomePage : Start
Route::get('/site/homepage', [HomepageController::class, 'index'])->name('homepage');
Route::get('/site/homepage/create', [HomepageController::class, 'create'])->name('homepage.create');
Route::get('/site/homepage/edit/{section_id}', [HomepageController::class, 'edit'])->name('homepage.edit');
// HomePage : End

Route::group(['prefix' => '/site', 'as' => 'site.'], function () {
    // Slider : Start
    Route::resource('/homepage/banners', BannerController::class);
    // Slider : Start

    // Top Super Categories : Start
    Route::get('/homepage/topsupercategories', [TopSuperCategories::class, 'index'])->name('topsupercategories.index');
    // Top Super Categories : End

    // Top Super Categories : Start
    Route::get('/homepage/topcategories', [TopCategories::class, 'index'])->name('topcategories.index');
    // Top Super Categories : End

    // Today's Deals : Start
    Route::get('/homepage/today-deals', [TodayDealsController::class, 'index'])->name('today-deals.index');
    // Today's Deals : End

});
