<?php

use Illuminate\Support\Facades\Route as RouteFacade;
use MohammadAlavi\Laragen\RequestSchema\ContentEncoding;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResolver;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResult;
use MohammadAlavi\Laragen\RequestSchema\RequestTarget;
use Tests\Laragen\Support\Doubles\E2E\E2EController;

describe('Query parameter integration', function (): void {
    it('resolves GET route validation rules as QUERY target', function (): void {
        RouteFacade::get('/test/search', [E2EController::class, 'store']);
        $route = RouteFacade::getRoutes()->getByAction(E2EController::class . '@store');

        $resolver = app(RequestSchemaResolver::class);
        $result = $resolver->resolve($route, E2EController::class, 'store');

        expect($result)->toBeInstanceOf(RequestSchemaResult::class)
            ->and($result->target)->toBe(RequestTarget::QUERY)
            ->and($result->encoding)->toBe(ContentEncoding::JSON);
    });

    it('resolves POST route validation rules as BODY target', function (): void {
        RouteFacade::post('/test/articles', [E2EController::class, 'store']);
        $route = RouteFacade::getRoutes()->getByAction(E2EController::class . '@store');

        $resolver = app(RequestSchemaResolver::class);
        $result = $resolver->resolve($route, E2EController::class, 'store');

        expect($result)->toBeInstanceOf(RequestSchemaResult::class)
            ->and($result->target)->toBe(RequestTarget::BODY)
            ->and($result->encoding)->toBe(ContentEncoding::JSON);
    });

    it('resolves DELETE route validation rules as QUERY target', function (): void {
        RouteFacade::delete('/test/items/{id}', [E2EController::class, 'store']);
        $route = RouteFacade::getRoutes()->getByAction(E2EController::class . '@store');

        $resolver = app(RequestSchemaResolver::class);
        $result = $resolver->resolve($route, E2EController::class, 'store');

        expect($result)->toBeInstanceOf(RequestSchemaResult::class)
            ->and($result->target)->toBe(RequestTarget::QUERY);
    });

    it('resolves HEAD route validation rules as QUERY target', function (): void {
        RouteFacade::addRoute('HEAD', '/test/ping', [E2EController::class, 'store']);
        $route = RouteFacade::getRoutes()->getByAction(E2EController::class . '@store');

        $resolver = app(RequestSchemaResolver::class);
        $result = $resolver->resolve($route, E2EController::class, 'store');

        expect($result)->toBeInstanceOf(RequestSchemaResult::class)
            ->and($result->target)->toBe(RequestTarget::QUERY);
    });
})->covers(RequestSchemaResolver::class);
