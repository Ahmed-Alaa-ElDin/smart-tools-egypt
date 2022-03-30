<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\GovernorateController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\SupercategoryController;
use App\Http\Controllers\Admin\ZoneController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'as' =>   'admin.',
    'prefix' => '/admin',
], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ############## Products Routes Start ##############
    Route::get('/products/export-excel', [ProductController::class, 'exportExcel'])->name('products.exportExcel');
    Route::get('/products/export-pdf', [ProductController::class, 'exportPDF'])->name('products.exportPDF');

    Route::get('/products/deleted-products', [ProductController::class, 'softDeletedProducts'])->name('products.softDeletedProducts');
    Route::resource('/products', ProductController::class);

    Route::resource('/brands', BrandController::class);
    // ############## Products Routes End ##############


    // ############## Categories Routes Start ##############
    Route::resource('/super-categories', SupercategoryController::class);

    Route::resource('/categories', CategoryController::class);

    Route::resource('/sub-categories', SubcategoryController::class);
    // ############## Categories Routes End ##############

    // ############## Users Routes Start ##############
    Route::get('/users/export-excel', [UserController::class, 'exportExcel'])->name('users.exportExcel');
    Route::get('/users/export-pdf', [UserController::class, 'exportPDF'])->name('users.exportPDF');
    Route::get('/users/deleted-users', [UserController::class, 'softDeletedUsers'])->name('users.softDeletedUsers');
    Route::resource('/users', UserController::class);

    Route::get('/roles/roles-permissions/{id}', [RoleController::class, 'showPermissions'])->name('roles.showPermissions');
    Route::get('/roles/roles-users/{id}', [RoleController::class, 'showUsers'])->name('roles.showUsers');
    Route::resource('/roles', RoleController::class);
    // ############## Users Routes End ##############


    // ############## Delivery System Routes Start ##############
    Route::get('/deliveries/deleted-delivery-companies', [DeliveryController::class, 'softDeletedDeliveries'])->name('deliveries.softDeletedDeliveries');
    Route::resource('/deliveries', DeliveryController::class);

    Route::get('/zones/delivery-zones/{delivery_id}/edit', [ZoneController::class, 'editZone'])->name('zones.deliveryZones.edit');
    Route::resource('/zones', ZoneController::class);

    Route::get('/countries/{country}/governorates-country', [CountryController::class, 'governoratesCountry'])->name('countries.governoratesCountry');
    Route::get('/countries/{country}/cities-country', [CountryController::class, 'citiesCountry'])->name('countries.citiesCountry');
    Route::get('/countries/{country}/users-country', [CountryController::class, 'usersCountry'])->name('countries.usersCountry');
    Route::get('/countries/{country}/deliveries-country', [CountryController::class, 'deliveriesCountry'])->name('countries.deliveriesCountry');
    Route::get('/countries/deleted-countries', [CountryController::class, 'softDeletedCountries'])->name('countries.softDeletedCountries');
    Route::resource('/countries', CountryController::class);

    Route::get('/governorates/{governorate}/cities-governorate', [GovernorateController::class, 'citiesGovernorate'])->name('governorates.citiesGovernorate');
    Route::get('/governorates/{governorate}/users-governorate', [GovernorateController::class, 'usersGovernorate'])->name('governorates.usersGovernorate');
    Route::get('/governorates/{governorate}/deliveries-governorate', [GovernorateController::class, 'deliveriesGovernorate'])->name('governorates.deliveriesGovernorate');
    Route::get('/governorates/deleted-governorates', [GovernorateController::class, 'softDeletedGovernorates'])->name('governorates.softDeletedGovernorates');
    Route::resource('/governorates', GovernorateController::class);

    Route::get('/cities/{city}/users-city', [CityController::class, 'usersCity'])->name('cities.usersCity');
    Route::get('/cities/{city}/deliveries-city', [CityController::class, 'deliveriesCity'])->name('cities.deliveriesCity');
    Route::get('/cities/deleted-cities', [CityController::class, 'softDeletedCities'])->name('cities.softDeletedCities');
    Route::resource('/cities', CityController::class);
    // ############## Delivery System Routes End ##############
});
