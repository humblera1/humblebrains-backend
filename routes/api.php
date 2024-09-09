<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::prefix('v1')->name('.v1')->group(function () {
        $prefix = '/api/v1';

        require __DIR__ . $prefix . '/auth.php';
    });
})->middleware('auth:sanctum');
