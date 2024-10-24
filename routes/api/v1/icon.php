<?php

use App\Http\Controllers\Api\v1\IconController;
use Illuminate\Support\Facades\Route;


Route::controller(IconController::class)
    ->prefix('icon')
    ->name('icon.')
    ->group(function () {
        Route::get('/get-icons', 'getIcons')->name('get-icons');
    });
