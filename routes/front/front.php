<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\HomepageController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\InvoiceRequestController;
use App\Models\InvoiceRequest;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['getCart'],
    'as' =>   'front.',
    'prefix' => '',
], function () {
    Route::get('/', [HomepageController::class, 'index'])->name('homepage');

    ################ User's Profile :: Start ##############
    Route::resource('/profile', ProfileController::class)->middleware('auth');
    ################ User's Profile :: End ##############

    ################ Product Controller :: Start ##############
    Route::get('/product/{id}/{slug}', [ProductController::class, 'show'])->name('product.show');
    ################ Product Controller :: End ##############

    ################ Cart & Order Controller :: Start ##############
    Route::get('/cart', [CartController::class, 'index'])->name('cart')->middleware(['cart_not_empty']);

    Route::get('/order/shipping', [OrderController::class, 'shipping'])->name('order.shipping')->middleware(['cart_not_empty']);
    Route::get('/order/billing', [OrderController::class, 'billing'])->name('order.billing')->middleware(['auth', 'can_deliver', 'cart_not_empty']);
    Route::get('/order/billing/check', [OrderController::class, 'billingCheck'])->name('order.billing.check');
    Route::get('/order/billing/checked', [OrderController::class, 'billingChecked'])->name('order.billing.checked');
    Route::get('/order/done', [OrderController::class, 'done'])->name('order.done')->middleware('auth');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index')->middleware('auth');
    Route::delete('/orders/{order_id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel')->middleware('auth');
    Route::get('/orders/{order_id}/edit', [OrderController::class, 'edit'])->name('orders.edit')->middleware('auth');
    Route::put('/orders/{order_id}', [OrderController::class, 'update'])->name('orders.update')->middleware('auth');
    Route::put('/orders/{old_order_id}/{new_older_id}', [OrderController::class, 'saveUpdates'])->name('orders.save-update')->middleware('auth');
    ################ Cart & Order Controller :: End ##############

    ################ Invoice Request Controller :: Start ##############
    Route::post('/invoice-request-store', [InvoiceRequestController::class, 'store'])->name('invoice-request.store')->middleware('auth');
    ################ Invoice Request Controller :: End ##############
});
