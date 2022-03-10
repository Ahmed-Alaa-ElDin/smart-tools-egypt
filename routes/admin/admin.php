<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\ZonesController;
use App\Models\City;
use App\Models\Destination;
use App\Models\Governorate;
use App\Models\Zone;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'as' =>   'admin.',
    'prefix' => '/admin',
], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ############## Users Routes Start ##############
    Route::get('/users/export-excel', [UsersController::class, 'exportExcel'])->name('users.exportExcel');
    Route::get('/users/export-pdf', [UsersController::class, 'exportPDF'])->name('users.exportPDF');
    Route::get('/users/deleted-users', [UsersController::class, 'softDeletedUsers'])->name('users.softDeletedUsers');
    Route::resource('/users', UsersController::class);


    Route::get('/roles/roles-permissions/{id}', [RolesController::class, 'showPermissions'])->name('roles.showPermissions');
    Route::get('/roles/roles-users/{id}', [RolesController::class, 'showUsers'])->name('roles.showUsers');
    Route::resource('/roles', RolesController::class);
    // ############## Users Routes End ##############


    // ############## Delivery System Routes Start ##############
    Route::get('/deliveries/deleted-delivery-companies', [DeliveryController::class, 'softDeletedDeliveries'])->name('deliveries.softDeletedDeliveries');
    Route::resource('/deliveries', DeliveryController::class);

    Route::get('/zones/delivery-zones/{delivery_id}/edit', [ZonesController::class, 'editZone'])->name('zones.deliveryZones.edit');
    Route::resource('/zones', ZonesController::class);
    // ############## Delivery System Routes End ##############

});
