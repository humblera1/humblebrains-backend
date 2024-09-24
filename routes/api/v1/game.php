<?php


use App\Http\Controllers\Api\v1\GameController;
use Illuminate\Support\Facades\Route;

Route::controller(GameController::class)
    ->prefix('game')
    ->name('game.')
    ->group(function () {
        Route::get('/levels', 'levels')->name('game-levels');
    });
