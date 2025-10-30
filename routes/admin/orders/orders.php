<?php

use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/orders/{order}/payment-history', [OrderController::class, 'paymentHistory'])->name('orders.payment-history');
Route::get('/orders/deleted-orders', [OrderController::class, 'softDeletedOrders'])->name('orders.softDeletedOrders');
Route::get('/orders/new-orders', [OrderController::class, 'newOrders'])->name('orders.new-orders');
Route::get('/orders/approved-orders', [OrderController::class, 'approvedOrders'])->name('orders.approved-orders');
Route::get('/orders/edited-orders', [OrderController::class, 'editedOrders'])->name('orders.edited-orders');
Route::get('/orders/ready-for-shipping-orders', [OrderController::class, 'readyForShipping'])->name('orders.ready-for-shipping-orders');
Route::get('/orders/shipped-orders', [OrderController::class, 'shippedOrders'])->name('orders.shipped-orders');
Route::get('/orders/suspended-orders', [OrderController::class, 'suspendedOrders'])->name('orders.suspended-orders');
Route::get('/orders/delivered-orders', [OrderController::class, 'deliveredOrders'])->name('orders.delivered-orders');
Route::get('/orders/uncompleted-orders', [OrderController::class, 'uncompletedOrders'])->name('orders.uncompleted-orders');
Route::resource('/orders', OrderController::class);
