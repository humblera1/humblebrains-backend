<?php


use App\Http\Controllers\Api\v1\CategoryController;
use Illuminate\Support\Facades\Route;

Route::controller(CategoryController::class)
    ->prefix('categories')
    ->name('categories.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });
