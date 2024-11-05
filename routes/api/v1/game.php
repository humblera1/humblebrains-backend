<?php


use App\Http\Controllers\Api\v1\GameController;
use Illuminate\Support\Facades\Route;

Route::controller(GameController::class)
    ->prefix('games')
    ->name('games.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/levels', 'levels')->name('levels');
        Route::get('/{game}','show')->name('show');
        Route::get('/{game}/total-achievements', 'totalAchievements')->name('total-achievements')->middleware('auth:sanctum');
        Route::get('/{game}/statistics', 'statistics')->name('statistics')->middleware('auth:sanctum');
    });
