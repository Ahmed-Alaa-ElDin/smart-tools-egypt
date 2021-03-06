<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products/export-excel', [ProductController::class, 'exportExcel'])->name('products.exportExcel');
Route::get('/products/export-pdf', [ProductController::class, 'exportPDF'])->name('products.exportPDF');
Route::get('/products/deleted-products', [ProductController::class, 'softDeletedProducts'])->name('products.softDeletedProducts');
Route::resource('/products', ProductController::class);

Route::get('/countries/{brand}/products-brand', [BrandController::class, 'productsBrand'])->name('brands.productsBrand');
Route::get('/brands/deleted-brands', [BrandController::class, 'softDeletedBrands'])->name('brands.softDeletedBrands');
Route::resource('/brands', BrandController::class);
