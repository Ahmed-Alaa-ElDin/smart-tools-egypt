<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'as' =>   'admin.',
    'prefix' => '/admin',
], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ############## Users Routs Start ##############
    Route::get('/users/export-excel',[UsersController::class,'exportExcel'])->name('users.exportExcel');
    Route::get('/users/export-pdf',[UsersController::class,'exportPDF'])->name('users.exportPDF');
    Route::resource('/users', UsersController::class);
    // ############## Users Routs End ##############
});
