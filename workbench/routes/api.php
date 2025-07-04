<?php

use Illuminate\Support\Facades\Route;
use Workbench\App\Http\Controllers\UpdateUserController;

Route::prefix('api')->group(function () {
    Route::get('/sag', [UpdateUserController::class, 'test']);
});
