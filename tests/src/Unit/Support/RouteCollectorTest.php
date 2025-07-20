<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Support\RouteCollector;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use Tests\src\Support\Doubles\Stubs\Builders\ControllerWithoutOperationStub;
use Tests\src\Support\Doubles\Stubs\Builders\ControllerWithoutPathItemStub;
use Tests\src\Support\Doubles\Stubs\Builders\ControllerWithPathItemAndOperationStub;
use Tests\src\Support\Doubles\Stubs\CollectibleClass;

describe(class_basename(RouteCollector::class), function (): void {
    it('can collect all routes that has both pathItem and operation', function (): void {
        $unexpectedUri = [
            '/has-no-path-item',
            '/has-no-operation',
        ];
        $expectedUri = [
            '/has-both-pathItem-and-operation',
            '/has-both-pathItem-and-operation',
            '/has-both-pathItem-and-operation',
            '/has-both-pathItem-and-operation',
            '/has-both-pathItem-and-operation',
        ];
        Route::get($expectedUri[0], ControllerWithPathItemAndOperationStub::class);
        Route::post($expectedUri[1], ControllerWithPathItemAndOperationStub::class);
        Route::get($unexpectedUri[0], ControllerWithoutPathItemStub::class);
        Route::put($expectedUri[2], ControllerWithPathItemAndOperationStub::class);
        Route::patch($expectedUri[3], ControllerWithPathItemAndOperationStub::class);
        Route::get($unexpectedUri[1], ControllerWithoutOperationStub::class);
        Route::delete($expectedUri[4], ControllerWithPathItemAndOperationStub::class);
        /** @var RouteCollector $routeCollector */
        $routeCollector = app(RouteCollector::class);

        $routes = $routeCollector->all();

        expect($routes)
            ->count()->toBeGreaterThanOrEqual(5)
            ->and($expectedUri)->each->toBeIn($routes->map(fn (RouteInfo $route) => $route->uri()))
            ->and($routes)->toContainOnlyInstancesOf(RouteInfo::class);
    });

    it('can filter routes by collection', function (): void {
        Route::get('/default-collection', ControllerWithPathItemAndOperationStub::class);
        Route::get('/test-collection', CollectibleClass::class);
        Route::put('/another-collection', ControllerWithPathItemAndOperationStub::class);
        Route::patch('/default-collection', ControllerWithPathItemAndOperationStub::class);
        Route::delete('/default-collection', ControllerWithPathItemAndOperationStub::class);
        /** @var RouteCollector $routeCollector */
        $routeCollector = app(RouteCollector::class);

        $routes = $routeCollector->whereInCollection('TestCollection');

        expect($routes)->toHaveCount(1)
            ->and($routes)->toContainOnlyInstancesOf(RouteInfo::class);
    });
})->covers(RouteCollector::class);
