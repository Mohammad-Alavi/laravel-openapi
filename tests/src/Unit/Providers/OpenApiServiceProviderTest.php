<?php

use Illuminate\Contracts\Foundation\Application;
use MohammadAlavi\LaravelOpenApi\Providers\OpenApiServiceProvider;

describe(class_basename(OpenApiServiceProvider::class), function (): void {
    it('correctly registers stuff', function (): void {
        app()->register(OpenApiServiceProvider::class);
        /** @var Application $app */
        $app = app();

        expect($app->get('config')->get('openapi.collections.default'))->toBe(
            (require __DIR__ . '/../../../../config/openapi.php')['collections']['default'],
        );
    });
})->covers(OpenApiServiceProvider::class);
