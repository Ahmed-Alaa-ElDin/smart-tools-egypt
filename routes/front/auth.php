<?php

use App\Http\Controllers\Front\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Front\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/'], function () {

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->middleware('guest')
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest')
        ->name('login.store');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');

    Route::GET('/register', [RegisteredUserController::class, 'create'])
        ->middleware('guest')
        ->name('register');

    Route::POST('/register', [RegisteredUserController::class, 'store'])
        ->middleware('guest')
        ->name('register.store');

    // Facebook Register / Login
    Route::get('/auth/facebook/redirect', [AuthenticatedSessionController::class, 'facebookRedirect'])->name('facebook.redirect');
    Route::get('/auth/facebook/callback', [AuthenticatedSessionController::class, 'facebookCallback']);

    // Google Register / Login
    Route::get('/auth/google/redirect', [AuthenticatedSessionController::class, 'googleRedirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [AuthenticatedSessionController::class, 'googleCallback']);
});
