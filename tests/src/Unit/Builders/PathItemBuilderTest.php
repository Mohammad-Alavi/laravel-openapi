<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Builders\PathItemBuilder;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use Tests\src\Support\Doubles\Stubs\Objects\ControllerWithFullPathItem;
use Tests\src\Support\Doubles\Stubs\Objects\InvocableController;

describe(class_basename(PathItemBuilder::class), function (): void {
    it('builds a PathItem with just operations', function (): void {
        $routeInfo = RouteInfo::create(
            Route::get('/test', [InvocableController::class, '__invoke']),
        );

        $pathItemBuilder = app(PathItemBuilder::class);
        $pathItem = $pathItemBuilder->build($routeInfo);

        expect($pathItem->compile())->toBeArray()
            ->toHaveKey('get');
    });

    it('builds a PathItem with all attributes from PathItem attribute', function (): void {
        $routeInfo = RouteInfo::create(
            Route::get('/test', [ControllerWithFullPathItem::class, 'index']),
        );

        $pathItemBuilder = app(PathItemBuilder::class);
        $pathItem = $pathItemBuilder->build($routeInfo);

        $compiled = $pathItem->compile();

        expect($compiled)->toBeArray()
            ->toHaveKey('get')
            ->toHaveKey('summary')
            ->toHaveKey('description')
            ->toHaveKey('servers')
            ->toHaveKey('parameters')
            ->and($compiled['summary'])->toBe('Test summary')
            ->and($compiled['description'])->toBe('Test description')
            ->and($compiled['servers'])->toBeArray()
            ->and($compiled['servers'][0]['url'])->toBe('https://laragen.io')
            ->and($compiled['parameters'])->toBeArray()
            ->and($compiled['parameters'])->not->toBeEmpty();
    });

    it('extracts URI path parameters at path level', function (): void {
        $routeInfo = RouteInfo::create(
            Route::get('/users/{id}', [InvocableController::class, '__invoke']),
        );

        $pathItemBuilder = app(PathItemBuilder::class);
        $pathItem = $pathItemBuilder->build($routeInfo);

        $compiled = $pathItem->compile();

        expect($compiled)->toHaveKey('parameters')
            ->and($compiled['parameters'])->toHaveCount(1)
            ->and($compiled['parameters'][0]['name'])->toBe('id')
            ->and($compiled['parameters'][0]['in'])->toBe('path');
    });
})->covers(PathItemBuilder::class);
