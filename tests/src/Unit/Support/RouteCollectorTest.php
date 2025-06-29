<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Support\RouteCollector;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use Pest\Expectation;
use Tests\src\Support\Doubles\Stubs\Builders\ControllerWithoutOperationStub;
use Tests\src\Support\Doubles\Stubs\Builders\ControllerWithoutPathItemStub;
use Tests\src\Support\Doubles\Stubs\Builders\ControllerWithPathItemAndOperationStub;
use Tests\src\Support\Doubles\Stubs\CollectibleClass;

describe(class_basename(RouteCollector::class), function (): void {
    it('can collect all routes', function (): void {
        Route::get('/has-both-pathItem-and-operation', ControllerWithPathItemAndOperationStub::class);
        Route::post('/has-both-pathItem-and-operation', ControllerWithPathItemAndOperationStub::class);
        Route::get('/has-no-path-item', ControllerWithoutPathItemStub::class);
        Route::put('/has-both-pathItem-and-operation', ControllerWithPathItemAndOperationStub::class);
        Route::patch('/has-both-pathItem-and-operation', ControllerWithPathItemAndOperationStub::class);
        Route::get('/has-no-operation', ControllerWithoutOperationStub::class);
        Route::delete('/has-both-pathItem-and-operation', ControllerWithPathItemAndOperationStub::class);
        /** @var RouteCollector $routeCollector */
        $routeCollector = app(RouteCollector::class);

        $routes = $routeCollector->all();

        expect($routes)->toHaveCount(5)
            ->and($routes)
            ->each(
                fn (Expectation $expectation): Expectation => $expectation->toBeInstanceOf(RouteInfo::class),
            );
    });

    it('can filter routes by collection', function (): void {
        Route::get('/default-collection', ControllerWithPathItemAndOperationStub::class);
        Route::get('/test-collection', CollectibleClass::class);
        Route::put('/default-collection', ControllerWithPathItemAndOperationStub::class);
        Route::patch('/default-collection', ControllerWithPathItemAndOperationStub::class);
        Route::delete('/default-collection', ControllerWithPathItemAndOperationStub::class);
        /** @var RouteCollector $routeCollector */
        $routeCollector = app(RouteCollector::class);

        $routes = $routeCollector->whereInCollection('TestCollection');

        expect($routes)->toHaveCount(1)
            ->and($routes)
            ->each(
                fn (Expectation $expectation): Expectation => $expectation->toBeInstanceOf(RouteInfo::class),
            );
    });
})->covers(RouteCollector::class);
