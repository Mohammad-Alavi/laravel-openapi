<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('laravel:openapi', function () {
    app(MohammadAlavi\LaravelOpenApi\Generator::class)->generate()->toJsonFile(
        'openapi',
        options: JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
    );
})->describe('Generate OpenAPI specification');
