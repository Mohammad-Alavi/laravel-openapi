<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Support\CollectionMatcher;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use Tests\src\Support\Doubles\Stubs\Objects\ControllerWithExtensions;

describe(class_basename(CollectionMatcher::class), function (): void {
    it('can check if route is in collection when action overrides controller', function (): void {
        // Default config: action_attribute_overrides_controller_attribute = true
        $routeInfo = RouteInfo::create(
            Route::get('/example', [ControllerWithExtensions::class, 'withExtensions']),
        );
        $matcher = new CollectionMatcher($routeInfo);

        expect($matcher->isInCollection('action-collection'))->toBeTrue()
            ->and($matcher->isInCollection('test'))->toBeFalse()
            ->and($matcher->isInCollection('example'))->toBeFalse();
    });

    it('can check if route is in collection when action does not override controller', function (): void {
        config()->set('openapi.collection.action_attribute_overrides_controller_attribute', false);

        $routeInfo = RouteInfo::create(
            Route::get('/example', [ControllerWithExtensions::class, 'withExtensions']),
        );
        $matcher = new CollectionMatcher($routeInfo);

        expect($matcher->isInCollection('action-collection'))->toBeTrue()
            ->and($matcher->isInCollection('test'))->toBeTrue()
            ->and($matcher->isInCollection('example'))->toBeTrue();
    });

    it('can check if route is in collection using controller attribute when action has none', function (): void {
        $routeInfo = RouteInfo::create(
            Route::get('/example', [ControllerWithExtensions::class, 'withoutExtensions']),
        );
        $matcher = new CollectionMatcher($routeInfo);

        expect($matcher->isInCollection('test'))->toBeTrue()
            ->and($matcher->isInCollection('example'))->toBeTrue()
            ->and($matcher->isInCollection('non-existent'))->toBeFalse();
    });

    it('can check if route has collection attribute', function (): void {
        $routeInfo = RouteInfo::create(
            Route::get('/example', [ControllerWithExtensions::class, 'withExtensions']),
        );
        $matcher = new CollectionMatcher($routeInfo);

        expect($matcher->hasCollectionAttribute())->toBeTrue();
    });

    it('returns false when route has no collection attribute', function (): void {
        $routeInfo = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $matcher = new CollectionMatcher($routeInfo);

        expect($matcher->hasCollectionAttribute())->toBeFalse();
    });

    it('can get all collection attributes', function (): void {
        $routeInfo = RouteInfo::create(
            Route::get('/example', [ControllerWithExtensions::class, 'withExtensions']),
        );
        $matcher = new CollectionMatcher($routeInfo);

        $collections = $matcher->getCollectionAttributes();

        expect($collections)->toHaveCount(2);
    });
})->covers(CollectionMatcher::class);
