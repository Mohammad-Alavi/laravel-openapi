<?php

declare(strict_types=1);

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\RequestSchema\ContentEncoding;
use MohammadAlavi\Laragen\RequestSchema\RequestDetector;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaBuilder;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResolver;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResult;
use MohammadAlavi\Laragen\RequestSchema\RequestTarget;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

describe('RequestSchema extensibility', function (): void {
    it('prepends user strategies before built-in ones via config', function (): void {
        config([
            'laragen.strategies.request.prepend' => [
                [CustomRequestDetector::class, CustomRequestSchemaBuilder::class],
            ],
        ]);

        // Re-register to pick up new config
        app()->forgetInstance(RequestSchemaResolver::class);
        $resolver = app(RequestSchemaResolver::class);

        $route = new Route('POST', '/test', ['controller' => 'FooController@store']);
        $result = $resolver->resolve($route, 'FooController', 'store');

        expect($result)->toBeInstanceOf(RequestSchemaResult::class)
            ->and($result->target)->toBe(RequestTarget::BODY);

        $compiled = $result->schema->compile();

        expect($compiled['properties'])->toHaveKey('custom_field');
    });

    it('appends user strategies after built-in ones via config', function (): void {
        config([
            'laragen.strategies.request.append' => [
                [FallbackRequestDetector::class, FallbackRequestSchemaBuilder::class],
            ],
        ]);

        app()->forgetInstance(RequestSchemaResolver::class);
        $resolver = app(RequestSchemaResolver::class);

        // Register a real route with a controller that has no FormRequest or SpatieData params
        \Illuminate\Support\Facades\Route::post('/fallback', [Tests\Laragen\Support\Doubles\E2E\E2EController::class, 'delete']);
        $route = \Illuminate\Support\Facades\Route::getRoutes()->getByAction(Tests\Laragen\Support\Doubles\E2E\E2EController::class . '@delete');

        $result = $resolver->resolve($route, Tests\Laragen\Support\Doubles\E2E\E2EController::class, 'delete');

        expect($result)->toBeInstanceOf(RequestSchemaResult::class);

        $compiled = $result->schema->compile();

        expect($compiled['properties'])->toHaveKey('fallback_field');
    });
})->covers(RequestSchemaResolver::class);

// Test doubles for extensibility tests

final readonly class CustomRequestDetector implements RequestDetector
{
    public function detect(Route $route, string $controllerClass, string $method): mixed
    {
        return ['custom' => true];
    }
}

final readonly class CustomRequestSchemaBuilder implements RequestSchemaBuilder
{
    public function build(mixed $detected, Route $route): RequestSchemaResult
    {
        return new RequestSchemaResult(
            Schema::from([
                'type' => 'object',
                'properties' => [
                    'custom_field' => ['type' => 'string'],
                ],
            ]),
            RequestTarget::BODY,
            ContentEncoding::JSON,
        );
    }
}

final readonly class FallbackRequestDetector implements RequestDetector
{
    public function detect(Route $route, string $controllerClass, string $method): mixed
    {
        return ['fallback' => true];
    }
}

final readonly class FallbackRequestSchemaBuilder implements RequestSchemaBuilder
{
    public function build(mixed $detected, Route $route): RequestSchemaResult
    {
        return new RequestSchemaResult(
            Schema::from([
                'type' => 'object',
                'properties' => [
                    'fallback_field' => ['type' => 'string'],
                ],
            ]),
            RequestTarget::BODY,
            ContentEncoding::JSON,
        );
    }
}
