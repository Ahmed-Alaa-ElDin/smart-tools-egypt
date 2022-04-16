<?php


use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Front\HomepageController;
use Illuminate\Support\Facades\Route;

Route::group([
    // 'middleware' => [''],
    'as' =>   'front.',
    'prefix' => '',
], function () {
    Route::get('/', [HomepageController::class, 'index'])->name('homepage');

});
