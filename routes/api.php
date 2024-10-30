<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.')
    ->middleware(
        [
//            'auth:sanctum',
            \App\Http\Middleware\ConvertResponseKeysToCamelCase::class,
            \App\Http\Middleware\ConvertRequestKeysToSnakeCase::class,
        ]
    )
    ->group(function () {
    Route::prefix('v1')->name('v1.')->group(function () {
        $prefix = '/api/v1';

        require __DIR__ . $prefix . '/auth.php';
        require __DIR__ . $prefix . '/game.php';
        require __DIR__ . $prefix . '/checkpoint.php';
        require __DIR__ . $prefix . '/icons.php';
        require __DIR__ . $prefix . '/words.php';
    });
});
