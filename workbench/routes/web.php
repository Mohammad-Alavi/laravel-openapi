<?php

use Illuminate\Support\Facades\Route;
use Workbench\App\Http\Controllers\UpdateUserController;

Route::get('/sag', [UpdateUserController::class, 'test']);
