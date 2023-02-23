<?php

use App\Http\Controllers\Admin\Setting\Homepage\BannerController;
use App\Http\Controllers\Admin\Setting\Homepage\HomepageController;
use App\Http\Controllers\Admin\Setting\Homepage\TodayDealsController;
use App\Http\Controllers\Admin\Setting\Homepage\TopBannerController;
use App\Http\Controllers\Admin\Setting\Homepage\TopBrandsController;
use App\Http\Controllers\Admin\Setting\Homepage\TopCategoriesController;
use App\Http\Controllers\Admin\Setting\Homepage\TopSubcategoriesController;
use App\Http\Controllers\Admin\Setting\Homepage\TopSuperCategoriesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'setting/', 'as' => 'setting.'], function () {

    // General : Start
    Route::group(['prefix' => 'general/', 'as' => 'general.'], function () {
        // Top Banner : Start
        Route::get('/top-banner', [TopBannerController::class, 'index'])->name('topbanner.index');
        // Top Banner : End
    });
    // General : End

    // HomePage : Start
    Route::get('homepage', [HomepageController::class, 'index'])->name('homepage');

    Route::group(['prefix' => 'homepage/', 'as' => 'homepage.'], function () {
        // Section : Start
        Route::get('create', [HomepageController::class, 'create'])->name('create');

        Route::get('edit/{section_id}', [HomepageController::class, 'edit'])->name('edit');
        // Section : End


        // Slider : Start
        Route::resource('/banners', BannerController::class);
        // Slider : End

        // Top Super Categories : Start
        Route::get('/topsupercategories', [TopSuperCategoriesController::class, 'index'])->name('topsupercategories.index');
        // Top Super Categories : End

        // Top Categories : Start
        Route::get('/topcategories', [TopCategoriesController::class, 'index'])->name('topcategories.index');
        // Top Categories : End

        // Top Sub Categories : Start
        Route::get('/topsubcategories', [TopSubcategoriesController::class, 'index'])->name('topsubcategories.index');
        // Top Sub Categories : End

        // Top Brands : Start
        Route::get('/topbrands', [TopBrandsController::class, 'index'])->name('topbrands.index');
        // Top Brands : End

        // Today's Deals : Start
        Route::get('/today-deals', [TodayDealsController::class, 'index'])->name('today-deals.index');
        // Today's Deals : End
    });
    // HomePage : End

});
