<?php

use App\Http\Controllers\Admin\AuthControllerAdmin;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/admin', 'as' => 'admin.'], function () {

    Route::get('/login', [AuthControllerAdmin::class, 'create'])
        ->middleware('guest')
        ->name('login');

    Route::post('/login', [AuthControllerAdmin::class, 'store'])
        ->middleware('guest');

    Route::post('/logout', [AuthControllerAdmin::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');
});
