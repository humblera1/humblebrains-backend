<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\Api\v1\UserController::class)
    ->prefix('users')
    ->name('users.')
    ->group(function () {
        Route::post('/set-avatar', 'setAvatar')->name('set-avatar');
        Route::post('/update', 'update')->name('update');
        Route::post('/change-password', 'changePassword')->name('change-password');
    });
