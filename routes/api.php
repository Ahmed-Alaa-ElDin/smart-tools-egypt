<?php

use App\Http\Controllers\Front\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Bosta Webhook
Route::post('/orders/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

// Paymob Webhook
Route::post('/orders/payment/check-processed', 'paymentCheckProcessed')->name('payment.check-processed');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->state;
});
