<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'/admin','as'=>'admin.'], function () {

    Route::get('/login', [AuthController::class, 'create'])
        ->middleware('guest')
        ->name('login');

    Route::post('/login', [AuthController::class, 'store'])
        ->middleware('guest');

    Route::post('/logout', [AuthController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');
});
