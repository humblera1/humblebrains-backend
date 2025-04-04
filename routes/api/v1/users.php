<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\Api\v1\UserController::class)
    ->prefix('users')
    ->name('users.')
    ->group(function () {
        Route::get('/statistics', 'statistics')->name('statistics')->middleware('auth:sanctum');
        Route::post('/me', 'me')->name('me');
        Route::post('/update', 'update')->name('update')->middleware('auth:sanctum');
        Route::post('/set-avatar', 'setAvatar')->name('set-avatar')->middleware('auth:sanctum');
        Route::post('/send-email-verification-notification', 'sendEmailVerificationNotification')->name('send-email-verification-notification');
    });

Route::get('/verify-email/{id}/{hash}', [\App\Http\Controllers\Api\v1\UserController::class, 'verifyEmail'])
    ->middleware(['auth:sanctum', 'signed'])
    ->name('verification.verify');
