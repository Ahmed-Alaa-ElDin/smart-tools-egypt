<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\RolesController;
use Illuminate\Support\Facades\Route;
use RealRashid\SweetAlert\Facades\Alert;

Route::group([
    'middleware' => ['auth'],
    'as' =>   'admin.',
    'prefix' => '/admin',
], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ############## Users Routes Start ##############
    Route::get('/users/export-excel',[UsersController::class,'exportExcel'])->name('users.exportExcel');
    Route::get('/users/export-pdf',[UsersController::class,'exportPDF'])->name('users.exportPDF');
    Route::get('/users/deleted-users',[UsersController::class,'softDeletedUsers'])->name('users.softDeletedUsers');
    Route::resource('/users', UsersController::class);
    Route::resource('/roles', RolesController::class);
    // ############## Users Routes End ##############

});
