<?php

use App\Http\Controllers\Api\v1\FileUploadController;
use Illuminate\Support\Facades\Route;

Route::controller(FileUploadController::class)
    ->prefix('files')
    ->name('files.')
    ->group(function () {
        Route::post('/validate-file', 'validateFile')->name('validate-file');
    });
