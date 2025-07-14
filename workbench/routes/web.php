<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Workbench\App\Http\Controllers\ShowUserController;

Route::get('/users/{id}', [ShowUserController::class, '__invoke']);

Route::get(
    'laragen/docs',
    static function (): BinaryFileResponse {
        return response()->file(__DIR__ . '/../../.laragen/openapi.json');
    },
);
