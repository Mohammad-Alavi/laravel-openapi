<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route as RouteFacade;
use MohammadAlavi\Laragen\Auth\AuthDetector;
use MohammadAlavi\Laragen\Auth\AuthScheme;
use MohammadAlavi\Laragen\Auth\SecuritySchemeRegistry;

describe('Auth Detection Integration', function (): void {
    it('detects auth:sanctum on a real route', function (): void {
        $route = RouteFacade::get('test/auth', fn () => 'ok')
            ->middleware(['api', 'auth:sanctum']);

        $detector = new AuthDetector();
        $result = $detector->detect($route);

        expect($result)->not->toBeNull()
            ->and($result->schemeName())->toBe('sanctum');
    });

    it('builds security for a detected scheme', function (): void {
        $route = RouteFacade::get('test/auth', fn () => 'ok')
            ->middleware(['api', 'auth:sanctum']);

        $detector = new AuthDetector();
        $registry = new SecuritySchemeRegistry();

        $authScheme = $detector->detect($route);
        $security = $registry->securityFor($authScheme);

        expect($security->toArray())->toBe([
            ['BearerAuth' => []],
        ]);
    });

    it('builds security for basic auth', function (): void {
        $route = RouteFacade::get('test/auth', fn () => 'ok')
            ->middleware(['api', 'auth.basic']);

        $detector = new AuthDetector();
        $registry = new SecuritySchemeRegistry();

        $authScheme = $detector->detect($route);
        $security = $registry->securityFor($authScheme);

        expect($security->toArray())->toBe([
            ['BasicAuth' => []],
        ]);
    });

    it('returns null for routes without auth', function (): void {
        $route = RouteFacade::get('test/no-auth', fn () => 'ok')
            ->middleware(['api']);

        $detector = new AuthDetector();
        $result = $detector->detect($route);

        expect($result)->toBeNull();
    });
})->covers(AuthDetector::class, AuthScheme::class, SecuritySchemeRegistry::class);
