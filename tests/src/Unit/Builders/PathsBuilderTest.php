<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Builders\PathsBuilder;
use MohammadAlavi\LaravelOpenApi\Support\RouteCollector;
use Tests\src\Support\Doubles\Stubs\Builders\ControllerWithPathItemAndOperationStub;

describe(class_basename(PathsBuilder::class), function (): void {
    it('can be created', function (): void {
        Route::get('/has-both-pathItem-and-operation', ControllerWithPathItemAndOperationStub::class);
        $routeCollector = app(RouteCollector::class);
        $routeInfo = $routeCollector->whereInCollection('Another');
        $pathsBuilder = app(PathsBuilder::class);

        $paths = $pathsBuilder->build($routeInfo);

        expect($paths->unserializeToArray())->toHaveCount(1)
            ->and($paths->unserializeToArray())->toBe([
                '/has-both-pathItem-and-operation' => [
                    'get' => [],
                ],
            ]);
    });
})->covers(PathsBuilder::class);
