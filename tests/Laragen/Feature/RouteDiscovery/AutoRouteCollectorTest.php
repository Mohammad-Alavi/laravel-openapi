<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route as RouteFacade;
use MohammadAlavi\Laragen\RouteDiscovery\AutoRouteCollector;
use MohammadAlavi\Laragen\RouteDiscovery\PatternMatcher;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use Tests\Laragen\Support\Doubles\ExtractController;

describe(class_basename(AutoRouteCollector::class), function (): void {
    it('collects routes matching include patterns', function (): void {
        RouteFacade::get('test-auto/users', [ExtractController::class, 'simpleRules']);
        RouteFacade::get('test-web/home', [ExtractController::class, 'simpleRules']);

        $collector = app(AutoRouteCollector::class);
        $routes = $collector->collect(new PatternMatcher(['test-auto/*'], []));

        expect($routes)->toHaveCount(1)
            ->and($routes->first())->toBeInstanceOf(RouteInfo::class)
            ->and($routes->first()->uri())->toBe('/test-auto/users');
    });

    it('excludes routes matching exclude patterns', function (): void {
        RouteFacade::get('test-auto/users', [ExtractController::class, 'simpleRules']);
        RouteFacade::get('test-auto/admin/settings', [ExtractController::class, 'simpleRules']);

        $collector = app(AutoRouteCollector::class);
        $routes = $collector->collect(new PatternMatcher(['test-auto/*'], ['test-auto/admin/*']));

        expect($routes)->toHaveCount(1)
            ->and($routes->first()->uri())->toBe('/test-auto/users');
    });

    it('excludes closure routes', function (): void {
        RouteFacade::get('test-auto/health', fn () => 'ok');
        RouteFacade::get('test-auto/users', [ExtractController::class, 'simpleRules']);

        $collector = app(AutoRouteCollector::class);
        $routes = $collector->collect(new PatternMatcher(['test-auto/*'], []));

        expect($routes)->toHaveCount(1)
            ->and($routes->first()->uri())->toBe('/test-auto/users');
    });

    it('returns empty collection when no routes match', function (): void {
        RouteFacade::get('test-web/home', [ExtractController::class, 'simpleRules']);

        $collector = app(AutoRouteCollector::class);
        $routes = $collector->collect(new PatternMatcher(['test-nomatch/*'], []));

        expect($routes)->toHaveCount(0);
    });

    it('collects multiple matching routes', function (): void {
        RouteFacade::get('test-auto/users', [ExtractController::class, 'simpleRules']);
        RouteFacade::post('test-auto/users', [ExtractController::class, 'simpleRules']);
        RouteFacade::get('test-auto/posts', [ExtractController::class, 'simpleRules']);

        $collector = app(AutoRouteCollector::class);
        $routes = $collector->collect(new PatternMatcher(['test-auto/*'], []));

        expect($routes)->toHaveCount(3);
    });
})->covers(AutoRouteCollector::class);
