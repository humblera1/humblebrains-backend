<?php

use App\Http\Controllers\Api\v1\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)
    ->prefix('auth')
    ->name('auth.')
    ->group(function () {
        Route::post('/register', 'register')->name('register');
        Route::post('/login', 'login')->name('login');
        Route::post('/change-password', 'changePassword')->name('change-password');
        Route::post('/forgot-password', 'forgotPassword')->name('forgot-password');
        Route::post('/reset-password', 'resetPassword')->name('reset-password');
    });
