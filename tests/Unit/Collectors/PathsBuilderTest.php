<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\PathsBuilder;
use MohammadAlavi\LaravelOpenApi\Services\RouteCollector;
use Tests\Doubles\Stubs\Collectors\ControllerWithPathItemAndOperationStub;

describe(class_basename(PathsBuilder::class), function (): void {
    it('can be created', function (): void {
        Route::get('/has-both-pathItem-and-operation', ControllerWithPathItemAndOperationStub::class);
        $routeCollector = app(RouteCollector::class);
        $routeInfo = $routeCollector->all();
        $pathsBuilder = app(PathsBuilder::class);

        $paths = $pathsBuilder->build($routeInfo);

        expect($paths->asArray())->toHaveCount(1)
            ->and($paths->asArray())->toBe([
                '/has-both-pathItem-and-operation' => [
                    'get' => [],
                ],
            ]);
    });
})->covers(PathsBuilder::class);
