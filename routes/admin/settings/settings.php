<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Setting\General\BannerController;
use App\Http\Controllers\Admin\Setting\General\NavLinkController;
use App\Http\Controllers\Admin\Setting\Homepage\SliderController;
use App\Http\Controllers\Admin\Setting\General\TopBannerController;
use App\Http\Controllers\Admin\Setting\Homepage\HomepageController;

use App\Http\Controllers\Admin\Setting\Homepage\TopBrandsController;
use App\Http\Controllers\Admin\Setting\Homepage\TodayDealsController;
use App\Http\Controllers\Admin\Setting\General\GeneralSettingController;
use App\Http\Controllers\Admin\Setting\General\GlobalSettingController;
use App\Http\Controllers\Admin\Setting\Homepage\SubsliderBannerController;
use App\Http\Controllers\Admin\Setting\Homepage\TopCategoriesController;
use App\Http\Controllers\Admin\Setting\Homepage\TopSubcategoriesController;
use App\Http\Controllers\Admin\Setting\Homepage\TopSuperCategoriesController;

Route::group(['prefix' => 'setting/', 'as' => 'setting.'], function () {

    // General : Start
    Route::get('/general', [GeneralSettingController::class, 'index'])->name('general');

    Route::group(['prefix' => 'general/', 'as' => 'general.'], function () {
        // Global Settings : Start
        Route::get('global-settings', [GlobalSettingController::class, 'edit'])->name('global-settings.edit');
        Route::put('global-settings', [GlobalSettingController::class, 'update'])->name('global-settings.update');
        // Global Settings : End 

        // Top Banner : Start
        Route::get('/top-banner', [TopBannerController::class, 'index'])->name('topbanner.index');
        // Top Banner : End

        // Nav Links : Start
        Route::resource('nav-link', NavLinkController::class);
        // Nav Links : End

        // Banners : Start
        Route::resource('banners', BannerController::class);
        // Banners : End
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
        Route::resource('/sliders', SliderController::class);
        // Slider : End

        // Subslider Banner : Start
        Route::resource('/subslider-banners', SubsliderBannerController::class);
        // Subslider Banner : End

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
