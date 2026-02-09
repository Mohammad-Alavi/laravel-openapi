<?php

declare(strict_types=1);

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\RequestSchema\SpatieData\SpatieDataRequestDetector;
use Tests\Laragen\Support\Doubles\DataObjects\UserData;
use Tests\Laragen\Support\Doubles\SpatieDataRequestController;

describe(class_basename(SpatieDataRequestDetector::class), function (): void {
    it('detects Spatie Data parameter in controller method', function (): void {
        $detector = new SpatieDataRequestDetector();
        $route = new Route('POST', '/test', ['controller' => SpatieDataRequestController::class . '@store']);

        $result = $detector->detect($route, SpatieDataRequestController::class, 'store');

        expect($result)->toBe(UserData::class);
    });

    it('returns null when Data is only return type, not parameter', function (): void {
        $detector = new SpatieDataRequestDetector();
        $route = new Route('GET', '/test/{id}', ['controller' => SpatieDataRequestController::class . '@show']);

        $result = $detector->detect($route, SpatieDataRequestController::class, 'show');

        expect($result)->toBeNull();
    });

    it('returns null when method has no parameters', function (): void {
        $detector = new SpatieDataRequestDetector();
        $route = new Route('GET', '/test', ['controller' => SpatieDataRequestController::class . '@noParams']);

        $result = $detector->detect($route, SpatieDataRequestController::class, 'noParams');

        expect($result)->toBeNull();
    });

    it('returns null when parameters are not Data subclasses', function (): void {
        $detector = new SpatieDataRequestDetector();
        $route = new Route('POST', '/test', ['controller' => SpatieDataRequestController::class . '@stringParam']);

        $result = $detector->detect($route, SpatieDataRequestController::class, 'stringParam');

        expect($result)->toBeNull();
    });

    it('returns null for non-existent method', function (): void {
        $detector = new SpatieDataRequestDetector();
        $route = new Route('GET', '/test', ['controller' => SpatieDataRequestController::class . '@nonExistent']);

        $result = $detector->detect($route, SpatieDataRequestController::class, 'nonExistent');

        expect($result)->toBeNull();
    });
})->covers(SpatieDataRequestDetector::class);
