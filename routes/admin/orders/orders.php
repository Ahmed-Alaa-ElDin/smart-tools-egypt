<?php

use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/orders/{order}/payment-history', [OrderController::class, 'paymentHistory'])->name('orders.payment-history');
Route::get('/orders/deleted-orders', [OrderController::class, 'softDeletedOrders'])->name('orders.softDeletedOrders');
Route::resource('/orders', OrderController::class);
