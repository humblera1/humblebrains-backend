<?php

use App\Http\Controllers\Api\v1\WordController;
use Illuminate\Support\Facades\Route;


Route::controller(WordController::class)
    ->prefix('words')
    ->name('words.')
    ->group(function () {
        Route::get('/get-words', 'getWords')->name('get-words');
    });
