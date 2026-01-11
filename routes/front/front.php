<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\BrandController;
use App\Http\Controllers\Front\OfferController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\PolicyController;
use App\Http\Controllers\Front\AboutUsController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\HomepageController;
use App\Http\Controllers\Front\WishlistController;
use App\Http\Controllers\Front\CollectionController;
use App\Http\Controllers\Front\ComparisonController;
use App\Http\Controllers\Front\SubcategoryController;
use App\Http\Controllers\Front\SupercategoryController;
use App\Http\Controllers\Front\InvoiceRequestController;

Route::group([
    'middleware' => ['getCart'],
    'as' => 'front.',
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
    // Last Box Offer
    Route::get('/offers/last-box-offer', [OfferController::class, 'lastBox'])->name('last-box-offer');
    // New Arrival Offer
    Route::get('/offers/new-arrival-offer', [OfferController::class, 'newArrival'])->name('new-arrival-offer');
    // Max Price Offer
    Route::get('/offers/max-price-offer', [OfferController::class, 'maxPrice'])->name('max-price-offer');
    // Custom Offers
    Route::resource('/offers', OfferController::class);
    ################ Offers :: End ##############

    ################ Cart & Order Controller :: Start ##############
    Route::get('/cart', [CartController::class, 'index'])->name('cart')->middleware(['cart_not_empty']);

    
    Route::get('/orders/shipping', [OrderController::class, 'shipping'])->name('orders.shipping')->middleware(['cart_not_empty']);
    
    Route::prefix('/orders')->middleware(['auth'])->controller(OrderController::class)->name('orders.')->group(function () {
        // Checkout
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout')->middleware(['cart_not_empty']);
        
        // Submit order
        Route::post('/submit', 'submit')->name('submit')->middleware(['cart_not_empty']);
        
        // Billing Options
        Route::get('/payment', 'payment')->name('payment')->middleware(['can_deliver', 'cart_not_empty']);

        // Check the paymob response
        Route::post('/payment/check-processed', 'paymentCheckProcessed')->name('payment.check-processed')->withoutMiddleware(['auth']);
        Route::get('/payment/check-response', 'paymentCheckResponse')->name('payment.check-response');

        // Confirm the order
        Route::get('/done', 'done')->name('done');

        // List of all orders
        Route::get('', 'index')->name('index');

        // Cancel the order
        Route::delete('/{order_id}/cancel/{temp_order_id?}', 'cancel')->name('cancel');

        // Edit the order
        Route::get('/{order}/edit', 'edit')->name('edit')->middleware('can:update,order');

        // Show the orders details after edits
        Route::post('/{order}/update-calc', 'updateCalc')->name('update-calc')->middleware('can:update,order');

        // Save the updated order
        Route::put('/{order_id}/{new_older_id}', 'update')->name('update')->where('new_older_id', '[0-9]+');

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

        // Change payment method
        Route::put('/{order_id}/change-payment-method', "changePaymentMethod")->name('change-payment-method');
    });
    ################ Cart & Order Controller :: End ##############

    ################ Wishlist :: Start ##############
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    ################ Wishlist :: End ##############

    ################ Comparison :: Start ##############
    Route::get('/comparison', [ComparisonController::class, 'index'])->name('comparison');
    ################ Comparison :: End ##############

    ################ Invoice Request Controller :: Start ##############
    Route::post('/invoice-request-store', [InvoiceRequestController::class, 'store'])->name('invoice-request.store')->middleware('auth');
    ################ Invoice Request Controller :: End ##############

    ################ Policies :: Start ##############
    Route::get('/delivery-policy', [PolicyController::class, 'delivery'])->name('policies.delivery');
    Route::get('/return-and-exchange-policy', [PolicyController::class, 'returnAndExchange'])->name('policies.return-and-exchange');
    Route::get('/privacy-policy', [PolicyController::class, 'privacy'])->name('policies.privacy');
    ################ Policies :: End ##############

    ################ About Us :: Start ##############
    Route::get('/branches', [AboutUsController::class, 'branches'])->name('about-us.branches');
    ################ About Us :: End ##############

    ################ Product Controller :: Start ##############
    Route::get('/{id}-{slug?}', [ProductController::class, 'show'])->name('products.show');
    ################ Product Controller :: End ##############

    ################ Collection Controller :: Start ##############
    Route::get('/c/{id}-{slug?}', [CollectionController::class, 'show'])->name('collections.show');
    ################ Collection Controller :: End ##############

    ################ Quote Request Controller :: Start ##############
    // Route::get('/quote-request', [QuoteRequestController::class, 'show'])->name('quote-request');
    ################ Quote Request Controller :: End ##############
});
