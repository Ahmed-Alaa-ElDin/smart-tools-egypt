<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'as' =>   'admin.',
    'prefix' => '/admin',
], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
