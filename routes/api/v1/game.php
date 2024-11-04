<?php


use App\Http\Controllers\Api\v1\GameController;
use Illuminate\Support\Facades\Route;

Route::controller(GameController::class)
    ->prefix('games')
    ->name('games.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/levels', 'levels')->name('levels');
        Route::get('/{game}', [GameController::class, 'show'])->name('show');

    });
