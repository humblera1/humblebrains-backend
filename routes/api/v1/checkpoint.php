<?php

use App\Http\Controllers\Api\v1\CheckpointController;
use Illuminate\Support\Facades\Route;

Route::controller(CheckpointController::class)
    ->prefix('checkpoint')
    ->name('checkpoint.')
    ->group(function () {
        Route::post('/finish-stage', 'finishStage')->name('finish-stage')->middleware('auth:sanctum');
        Route::post('/finish-checkpoint', 'finishCheckpoint')->name('finish-checkpoint')->middleware('auth:sanctum');
    });
