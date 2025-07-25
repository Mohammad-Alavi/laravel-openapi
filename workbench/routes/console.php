<?php

use Illuminate\Support\Facades\Artisan;

use function Laravel\Prompts\select;

Artisan::command(
    'laragen:generate',
    function () {
        $this->info('Generating OpenAPI specification...');
        $collection = select(
            'Select the collection to generate OpenAPI specification for:',
            array_keys(config('openapi.collections')),
            default: 'default',
        );
        app(MohammadAlavi\LaravelOpenApi\Generator::class)->generate($collection)->toJsonFile(
            'openapi',
            './.laragen',
            JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
        );
        $this->info('OpenAPI specification generated successfully for collection: ' . $collection);
    },
)->describe('Generate OpenAPI specification');
