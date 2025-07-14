<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command(
    'laragen:generate',
    function () {
        foreach (config('openapi.collections') as $key => $value) {
            app(MohammadAlavi\LaravelOpenApi\Generator::class)->generate($key)->toJsonFile(
                'openapi',
                './.laragen',
                JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            );
        }
    },
)->describe('Generate OpenAPI specification');
