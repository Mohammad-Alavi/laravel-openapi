<?php

declare(strict_types=1);

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\Auth\AuthDetector;

describe(class_basename(AuthDetector::class), function (): void {
    it('detects auth:sanctum middleware as bearer', function (): void {
        $route = createRouteWithMiddleware(['api', 'auth:sanctum']);

        $result = (new AuthDetector())->detect($route);

        expect($result)->not->toBeNull()
            ->and($result->schemeName())->toBe('sanctum')
            ->and($result->guardName())->toBe('sanctum');
    });

    it('detects auth:api middleware as bearer', function (): void {
        $route = createRouteWithMiddleware(['api', 'auth:api']);

        $result = (new AuthDetector())->detect($route);

        expect($result)->not->toBeNull()
            ->and($result->schemeName())->toBe('api')
            ->and($result->guardName())->toBe('api');
    });

    it('detects auth.basic middleware as basic', function (): void {
        $route = createRouteWithMiddleware(['api', 'auth.basic']);

        $result = (new AuthDetector())->detect($route);

        expect($result)->not->toBeNull()
            ->and($result->schemeName())->toBe('basic')
            ->and($result->guardName())->toBeNull();
    });

    it('detects generic auth middleware with guard as bearer', function (): void {
        $route = createRouteWithMiddleware(['api', 'auth:web']);

        $result = (new AuthDetector())->detect($route);

        expect($result)->not->toBeNull()
            ->and($result->schemeName())->toBe('web')
            ->and($result->guardName())->toBe('web');
    });

    it('detects auth middleware without guard as bearer with default name', function (): void {
        $route = createRouteWithMiddleware(['api', 'auth']);

        $result = (new AuthDetector())->detect($route);

        expect($result)->not->toBeNull()
            ->and($result->schemeName())->toBe('default')
            ->and($result->guardName())->toBe('default');
    });

    it('returns null when no auth middleware is present', function (): void {
        $route = createRouteWithMiddleware(['api', 'throttle:60,1']);

        $result = (new AuthDetector())->detect($route);

        expect($result)->toBeNull();
    });

    it('returns null for empty middleware', function (): void {
        $route = createRouteWithMiddleware([]);

        $result = (new AuthDetector())->detect($route);

        expect($result)->toBeNull();
    });

    it('detects the first auth middleware when multiple are present', function (): void {
        $route = createRouteWithMiddleware(['auth:sanctum', 'auth.basic']);

        $result = (new AuthDetector())->detect($route);

        expect($result)->not->toBeNull()
            ->and($result->schemeName())->toBe('sanctum');
    });
})->covers(AuthDetector::class);

function createRouteWithMiddleware(array $middleware): Route
{
    $route = new Route(['GET'], '/test', ['uses' => fn () => 'ok']);
    $route->middleware($middleware);

    return $route;
}
