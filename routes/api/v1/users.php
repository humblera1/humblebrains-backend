<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\Api\v1\UserController::class)
    ->prefix('users')
    ->name('users.')
    ->group(function () {
        Route::post('/me', 'me')->name('me');
        Route::post('/update', 'update')->name('update');
        Route::post('/set-avatar', 'setAvatar')->name('set-avatar');
    });
