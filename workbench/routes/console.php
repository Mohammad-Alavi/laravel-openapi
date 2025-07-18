<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command(
    'laragen:generate {collection=default}',
    function () {
        $this->info('Generating OpenAPI specification...');
        $collection = $this->argument('collection');
        app(MohammadAlavi\LaravelOpenApi\Generator::class)->generate($collection)->toJsonFile(
            'openapi',
            './.laragen',
            JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
        );
        $this->info('OpenAPI specification generated successfully for collection: ' . $collection);
    },
)->describe('Generate OpenAPI specification');
