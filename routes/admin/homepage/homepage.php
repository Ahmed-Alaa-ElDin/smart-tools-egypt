<?php

use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\HomepageBannerController;
use Illuminate\Support\Facades\Route;

// HomePage : Start
Route::get('/site/homepage', [HomepageController::class,'index'])->name('homepage');
Route::get('/site/homepage/create', [HomepageController::class,'create'])->name('homepage.create');
// HomePage : End

// Slider : Start
Route::resource('/site/homepage/banners', HomepageBannerController::class);
// Slider : Start

