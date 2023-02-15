<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// ############## Users Routes :: Start ##############
Route::get('/users/export-excel', [UserController::class, 'exportExcel'])->name('users.exportExcel');
Route::get('/users/export-pdf', [UserController::class, 'exportPDF'])->name('users.exportPDF');
Route::get('/users/deleted-users', [UserController::class, 'softDeletedUsers'])->name('users.softDeletedUsers');
Route::resource('/users', UserController::class);

Route::get('/roles/roles-permissions/{id}', [RoleController::class, 'showPermissions'])->name('roles.showPermissions');
Route::get('/roles/roles-users/{id}', [RoleController::class, 'showUsers'])->name('roles.showUsers');
Route::resource('/roles', RoleController::class);
// ############## Users Routes :: End ##############


// ############## Customers :: Start ##############
Route::get('/customers/export-excel', [CustomerController::class, 'exportExcel'])->name('customers.exportExcel');
Route::get('/customers/export-pdf', [CustomerController::class, 'exportPDF'])->name('customers.exportPDF');
Route::get('/customers/deleted-customers', [CustomerController::class, 'softDeletedUsers'])->name('customers.softDeletedUsers');
Route::resource('/customers', CustomerController::class);
// ############## Customers :: End ##############
