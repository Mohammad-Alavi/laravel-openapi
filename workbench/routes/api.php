<?php

use Illuminate\Support\Facades\Route;
use Workbench\App\Http\Controllers\CreateUserController;

Route::prefix('api')->group(function () {
    Route::post('/users', [CreateUserController::class, '__invoke']);
});
