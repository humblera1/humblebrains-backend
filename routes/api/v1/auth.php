<?php

use App\Http\Controllers\Api\v1\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)
    ->prefix('users')
    ->name('users.')
    ->group(function () {
        Route::get('/test', 'test')->name('test');
        Route::post('/login', 'login')->name('login');
        Route::get('/me', 'me')->name('me');
    });
