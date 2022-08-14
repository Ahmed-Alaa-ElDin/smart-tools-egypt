<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\HomepageController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\InvoiceRequestController;
use App\Models\InvoiceRequest;
use Illuminate\Routing\RouteGroup;
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

    Route::get('/orders/shipping', [OrderController::class, 'shipping'])->name('order.shipping')->middleware(['cart_not_empty']);

    Route::prefix('/orders')->middleware('auth')->controller(OrderController::class)->name('orders.')->group(function () {
        Route::get('/billing', 'billing')->name('billing')->middleware(['can_deliver', 'cart_not_empty']);
        Route::get('/billing/check', 'billingCheck')->name('billing.check');
        Route::get('/billing/checked', 'billingChecked')->name('billing.checked');
        Route::get('/done', 'done')->name('done');

        Route::get('', 'index')->name('index');
        Route::delete('/{order_id}/cancel/{new_order_id?}', 'cancel')->name('cancel');
        Route::get('/{order_id}/edit', 'edit')->name('edit');
        Route::put('/{order_id}', 'update')->name('update');
        Route::put('/{old_order_id}/{new_older_id}', 'saveUpdates')->name('save-update');

        Route::get('/{order_id}/payment', 'goToPayment')->name('payment');
    });

    Route::post('/orders/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    ################ Cart & Order Controller :: End ##############

    ################ Invoice Request Controller :: Start ##############
    Route::post('/invoice-request-store', [InvoiceRequestController::class, 'store'])->name('invoice-request.store')->middleware('auth');
    ################ Invoice Request Controller :: End ##############
});
