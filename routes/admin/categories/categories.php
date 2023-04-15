<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\SupercategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/countries/{supercategory}/categories-supercategory', [SupercategoryController::class, 'categoriesSupercategory'])->name('supercategories.categoriesSupercategory');
Route::get('/countries/{supercategory}/subcategories-supercategory', [SupercategoryController::class, 'subcategoriesSupercategory'])->name('supercategories.subcategoriesSupercategory');
Route::get('/supercategories/deleted-supercategories', [SupercategoryController::class, 'softDeletedSupercategories'])->name('supercategories.softDeletedSupercategories');
Route::resource('/supercategories', SupercategoryController::class);

Route::get('/countries/{category}/subcategories-category', [CategoryController::class, 'subcategoriesCategory'])->name('categories.subcategoriesCategory');
Route::get('/categories/deleted-categories', [CategoryController::class, 'softDeletedCategories'])->name('categories.softDeletedCategories');
Route::resource('/categories', CategoryController::class);

Route::get('/countries/{subcategory}/products-subcategory', [SubcategoryController::class, 'productsSubcategory'])->name('subcategories.productsSubcategory');
Route::get('/subcategories/deleted-subcategories', [SubcategoryController::class, 'softDeletedSubcategories'])->name('subcategories.softDeletedSubcategories');
Route::resource('/subcategories', SubcategoryController::class);
