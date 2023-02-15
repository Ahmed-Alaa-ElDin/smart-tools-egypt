<?php

use App\Http\Controllers\Admin\Homepage\BannerController;
use App\Http\Controllers\Admin\Homepage\HomepageController;
use App\Http\Controllers\Admin\Homepage\TodayDealsController;
use App\Http\Controllers\Admin\Homepage\TopBannerController;
use App\Http\Controllers\Admin\Homepage\TopBrandsController;
use App\Http\Controllers\Admin\Homepage\TopCategoriesController;
use App\Http\Controllers\Admin\Homepage\TopSubcategoriesController;
use App\Http\Controllers\Admin\Homepage\TopSuperCategoriesController;
use Illuminate\Support\Facades\Route;

// HomePage : Start
Route::get('/site/homepage', [HomepageController::class, 'index'])->name('homepage');
Route::get('/site/homepage/create', [HomepageController::class, 'create'])->name('homepage.create');
Route::get('/site/homepage/edit/{section_id}', [HomepageController::class, 'edit'])->name('homepage.edit');
// HomePage : End

Route::group(['prefix' => '/site', 'as' => 'site.'], function () {
    // Slider : Start
    Route::get('/homepage/top-banner', [TopBannerController::class,'index'])->name('topbanner.index');
    // Slider : End

    // Slider : Start
    Route::resource('/homepage/banners', BannerController::class);
    // Slider : End

    // Top Super Categories : Start
    Route::get('/homepage/topsupercategories', [TopSuperCategoriesController::class, 'index'])->name('topsupercategories.index');
    // Top Super Categories : End

    // Top Categories : Start
    Route::get('/homepage/topcategories', [TopCategoriesController::class, 'index'])->name('topcategories.index');
    // Top Categories : End

    // Top Sub Categories : Start
    Route::get('/homepage/topsubcategories', [TopSubcategoriesController::class, 'index'])->name('topsubcategories.index');
    // Top Sub Categories : End

    // Top Brands : Start
    Route::get('/homepage/topbrands', [TopBrandsController::class, 'index'])->name('topbrands.index');
    // Top Brands : End

    // Today's Deals : Start
    Route::get('/homepage/today-deals', [TodayDealsController::class, 'index'])->name('today-deals.index');
    // Today's Deals : End

});
