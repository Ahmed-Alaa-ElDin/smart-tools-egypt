<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/orders/deleted-orders', [OrderController::class, 'softDeletedOrders'])->name('orders.softDeletedOrders');
Route::resource('/orders', OrderController::class);
