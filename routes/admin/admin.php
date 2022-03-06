<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\ZonesController;
use Illuminate\Support\Facades\Route;

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


    Route::get('/roles/roles-permissions/{id}',[RolesController::class,'showPermissions'])->name('roles.showPermissions');
    Route::get('/roles/roles-users/{id}',[RolesController::class,'showUsers'])->name('roles.showUsers');
    Route::resource('/roles', RolesController::class);
    // ############## Users Routes End ##############


    // ############## Delivery System Routes Start ##############
    Route::resource('/deliveries', DeliveryController::class);

    Route::get('/zones/delivery-zones/{delivery_id}',[ZonesController::class,'createZone'])->name('roles.deliveryZones.create');
    Route::resource('/zones', ZonesController::class);
    // ############## Delivery System Routes End ##############

});
