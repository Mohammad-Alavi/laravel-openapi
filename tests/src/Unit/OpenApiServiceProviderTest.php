<?php

use Illuminate\Contracts\Foundation\Application;
use MohammadAlavi\LaravelOpenApi\Providers\OpenApiServiceProvider;

describe('OpenApiServiceProvider', function (): void {
    it('correctly registers stuff', function (): void {
        app()->register(OpenApiServiceProvider::class);
        /** @var Application $app */
        $app = app();

        expect($app->get('config')->get('openapi'))->toBe(
            require __DIR__ . '/../../../config/openapi.php',
        );
    });
})->covers(OpenApiServiceProvider::class);
