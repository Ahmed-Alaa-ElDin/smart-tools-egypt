<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\HomepageController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\InvoiceRequestController;
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


    ################ Cart & Order Controller :: Start ##############
    Route::get('/cart', [CartController::class, 'index'])->name('cart')->middleware(['cart_not_empty']);

    Route::get('/orders/shipping', [OrderController::class, 'shipping'])->name('order.shipping')->middleware(['cart_not_empty']);

    Route::prefix('/orders')->middleware('auth')->controller(OrderController::class)->name('orders.')->group(function () {
        // Billing Options
        Route::get('/billing', 'billing')->name('billing')->middleware(['can_deliver', 'cart_not_empty']);

        // Check the paymob response
        Route::get('/billing/check', 'billingCheck')->name('billing.check');

        // Confirm the order
        Route::get('/done', 'done')->name('done');

        // List of all orders
        Route::get('', 'index')->name('index');

        // Cancel the order
        Route::delete('/{order_id}/cancel/{new_order_id?}', 'cancel')->name('cancel');

        // Edit the order
        Route::get('/{order_id}/edit', 'edit')->name('edit');

        // Show the orders details after edits
        Route::post('/{order_id}/update-calc', 'updateCalc')->name('update-calc');

        // Save the updated order
        Route::put('/{order_id}/{new_older_id}', 'update')->name('update');

        // return total order
        Route::delete('/{order_id}/return/{new_order_id?}', 'return')->name('return');

        // return products from the order
        Route::get('/{order_id}/return', 'return')->name('return');

        // preview the returned products
        Route::post('/{order_id}/return-calc', 'returnCalc')->name('return-calc');

        // Confirm the Return order
        Route::put('/{order_id}', 'returnConfirm')->name('return-confirm');

        // Cancel the Return order
        Route::delete('/{order_id}/return-cancel', 'returnCancel')->name('return-cancel');

        // Go to Paymob Iframe
        Route::get('/{order_id}/payment', 'goToPayment')->name('payment');

        // track the order
        Route::get('/{order_id}/track', 'track')->name('track');
    });
    ################ Cart & Order Controller :: End ##############

    ################ Invoice Request Controller :: Start ##############
    Route::post('/invoice-request-store', [InvoiceRequestController::class, 'store'])->name('invoice-request.store')->middleware('auth');
    ################ Invoice Request Controller :: End ##############

    ################ Product Controller :: Start ##############
    Route::get('/{id}-{slug?}', [ProductController::class, 'show'])->name('product.show');
    ################ Product Controller :: End ##############

});
