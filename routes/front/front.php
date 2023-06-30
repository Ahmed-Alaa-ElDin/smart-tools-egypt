<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\SupercategoryController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\BrandController;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\Front\HomepageController;
use App\Http\Controllers\Front\InvoiceRequestController;
use App\Http\Controllers\Front\CollectionController;
use App\Http\Controllers\Front\ComparisonController;
use App\Http\Controllers\Front\FavoriteController;
use App\Http\Controllers\Front\OfferController;
use App\Http\Controllers\Front\SubcategoryController;
use App\Http\Controllers\Front\WishlistController;

Route::group([
    'middleware' => ['getCart'],
    'as' =>   'front.',
    'prefix' => '',
], function () {
    Route::get('/', [HomepageController::class, 'index'])->name('homepage');

    ################ Search :: Start ##############
    Route::get('/search', [HomepageController::class, 'search'])->name('search');
    ################ Search :: End ##############

    ################ Section Products List :: Start ##############
    Route::get('/section-products/{section_id}', [HomepageController::class, 'showProductList'])->name('section-products');
    ################ Section Products List :: End ##############

    ################ User's Profile :: Start ##############
    Route::resource('/profile', ProfileController::class)->middleware('auth');
    ################ User's Profile :: End ##############

    ################ Brands :: Start ##############
    Route::resource('/brands', BrandController::class);
    ################ Brands :: End ##############

    ################ Supercategory :: Start ##############
    Route::resource('/supercategories', SupercategoryController::class);
    Route::get('/supercategories/{supercategory_id}/subcategories', [SupercategoryController::class, 'subcategories'])->name('supercategory.subcategories');
    Route::get('/supercategories/{supercategory_id}/products', [SupercategoryController::class, 'products'])->name('supercategory.products');
    ################ Supercategory :: End ##############

    ################ Category :: Start ##############
    Route::resource('/categories', CategoryController::class);
    Route::get('/categories/{category_id}/products', [CategoryController::class, 'products'])->name('category.products');
    ################ Category :: End ##############

    ################ Subcategory :: Start ##############
    Route::resource('/subcategories', SubcategoryController::class);
    ################ Subcategory :: End ##############

    ################ Offers :: Start ##############
    Route::resource('/offers', OfferController::class);
    ################ Offers :: End ##############

    ################ Cart & Order Controller :: Start ##############
    Route::get('/cart', [CartController::class, 'index'])->name('cart')->middleware(['cart_not_empty']);

    Route::get('/orders/shipping', [OrderController::class, 'shipping'])->name('order.shipping')->middleware(['cart_not_empty']);

    Route::prefix('/orders')->middleware('auth')->controller(OrderController::class)->name('orders.')->group(function () {
        // Billing Options
        Route::get('/payment', 'payment')->name('payment')->middleware(['can_deliver', 'cart_not_empty']);

        // Check the paymob response
        Route::get('/payment/check', 'paymentCheck')->name('payment.check');

        // Confirm the order
        Route::get('/done', 'done')->name('done');

        // List of all orders
        Route::get('', 'index')->name('index');

        // Cancel the order
        Route::delete('/{order_id}/cancel/{temp_order_id?}', 'cancel')->name('cancel');

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
        Route::get('/{order_id}/paymob', 'goToPayment')->name('paymob.pay');

        // Go to Paymob Refund
        Route::get('/{order_id}/paymob/refund', 'goToRefund')->name('paymob.refund');

        // track the order
        Route::get('/{order_id}/track', 'track')->name('track');
    });
    ################ Cart & Order Controller :: End ##############

    ################ Wishlist :: Start ##############
    Route::get('/wishlist',[WishlistController::class,'index'])->name('wishlist');
    ################ Wishlist :: End ##############

    ################ Comparison :: Start ##############
    Route::get('/comparison',[ComparisonController::class,'index'])->name('comparison');
    ################ Comparison :: End ##############

    ################ Invoice Request Controller :: Start ##############
    Route::post('/invoice-request-store', [InvoiceRequestController::class, 'store'])->name('invoice-request.store')->middleware('auth');
    ################ Invoice Request Controller :: End ##############

    ################ Product Controller :: Start ##############
    Route::get('/{id}-{slug?}', [ProductController::class, 'show'])->name('products.show');
    ################ Product Controller :: End ##############

    ################ Collection Controller :: Start ##############
    Route::get('/c/{id}-{slug?}', [CollectionController::class, 'show'])->name('collections.show');
    ################ Collection Controller :: End ##############
});
