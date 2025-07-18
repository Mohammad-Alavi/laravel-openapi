<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

Route::get(
    'laragen/docs',
    static function (): BinaryFileResponse {
        return response()->file(__DIR__ . '/../../.laragen/openapi.json');
    },
);
