<?php

use App\Http\Controllers\Api\v1\IconController;
use Illuminate\Support\Facades\Route;


Route::controller(IconController::class)
    ->prefix('icons')
    ->name('icons.')
    ->group(function () {
        Route::get('/get-icons', 'getIcons')->name('get-icons');
    });
