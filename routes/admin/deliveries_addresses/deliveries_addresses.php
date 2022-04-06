<?php

use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\GovernorateController;
use App\Http\Controllers\Admin\ZoneController;
use Illuminate\Support\Facades\Route;

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
