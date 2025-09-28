<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\LaravelOpenApi\Support\RouteCollector;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use Tests\src\Support\Doubles\Stubs\Builders\ControllerWithPathItemAndOperationStub;
use Tests\src\Support\Doubles\Stubs\CollectibleClass;
use Tests\src\Support\Doubles\Stubs\Objects\ExplicitDefaultCollectionController;
use Tests\src\Support\Doubles\Stubs\Objects\ExplicitDefaultCollectionControllerAction;
use Tests\src\Support\Doubles\Stubs\Objects\ExplicitOverriddenDefaultCollectionControllerAction;
use Tests\src\Support\Doubles\Stubs\Objects\ImplicitDefaultCollectionController;
use Tests\src\Support\Doubles\Stubs\Objects\InvocableController;
use Tests\src\Support\Doubles\Stubs\Objects\MultiActionController;

describe(class_basename(RouteCollector::class), function (): void {
    it('can filter routes by collection', function (): void {
        Route::get('/default-collection', ControllerWithPathItemAndOperationStub::class);
        Route::get('/test-collection', CollectibleClass::class);
        Route::put('/another-collection', ControllerWithPathItemAndOperationStub::class);
        Route::patch('/default-collection', ControllerWithPathItemAndOperationStub::class);
        Route::delete('/default-collection', ControllerWithPathItemAndOperationStub::class);
        /** @var RouteCollector $routeCollector */
        $routeCollector = app(RouteCollector::class);

        $routes = $routeCollector->whereShouldBeCollectedFor('TestCollection');

        expect($routes)->toHaveCount(1)
            ->and($routes)->toContainOnlyInstancesOf(RouteInfo::class);
    });

    it(
        'can configure default collection collecting behavior',
        function (bool $include, int $expectedCount): void {
            config(['openapi.collection.default.include_routes_without_attribute' => $include]);
            Route::get('', ExplicitDefaultCollectionController::class);
            Route::delete('', ExplicitDefaultCollectionControllerAction::class);
            Route::put('', ImplicitDefaultCollectionController::class);
            Route::post('', ExplicitOverriddenDefaultCollectionControllerAction::class);
            /** @var RouteCollector $routeCollector */
            $routeCollector = app(RouteCollector::class);

            $routes = $routeCollector->whereShouldBeCollectedFor(Generator::COLLECTION_DEFAULT);

            expect($routes->count())->toBe($expectedCount)
                ->and($routes)->toContainOnlyInstancesOf(RouteInfo::class);
        },
    )->with([
        'include routes without Collection attribute' => [true, 5],
        'do not include routes without Collection attribute' => [false, 3],
    ]);
})->covers(RouteCollector::class);
