<?php

use Illuminate\Support\Facades\Route;
use Workbench\App\Http\Controllers\ShowUserController;

Route::get('/users/{id}', [ShowUserController::class, '__invoke']);
