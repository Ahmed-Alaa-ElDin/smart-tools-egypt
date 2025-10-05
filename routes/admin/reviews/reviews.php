<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ReviewController;

// Reviews : Start
Route::resource('reviews', ReviewController::class);
Route::get('reviews/pending', [ReviewController::class, 'pending'])->name('reviews.pending');
// Reviews : End
