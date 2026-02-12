<?php

use Illuminate\Support\Facades\Route as RouteFacade;
use MohammadAlavi\Laragen\RequestSchema\ContentEncoding;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResult;
use MohammadAlavi\Laragen\RequestSchema\RequestTarget;
use MohammadAlavi\Laragen\RequestSchema\ValidationRules\ValidationRulesDetector;
use MohammadAlavi\Laragen\RequestSchema\ValidationRules\ValidationRulesSchemaBuilder;
use Tests\Laragen\Support\Doubles\E2E\E2EController;
use Tests\Laragen\Support\Doubles\E2E\E2EFormRequest;

describe('ValidationRules strategy', function (): void {
    describe(class_basename(ValidationRulesDetector::class), function (): void {
        it('detects FormRequest validation rules on a route', function (): void {
            RouteFacade::post('/test/articles', [E2EController::class, 'store']);
            $route = RouteFacade::getRoutes()->getByAction(E2EController::class . '@store');

            $detector = app(ValidationRulesDetector::class);
            $result = $detector->detect($route, E2EController::class, 'store');

            expect($result)->not->toBeNull()
                ->and($result->rules)->not->toBeEmpty()
                ->and($result->formRequestClass)->toBe(E2EFormRequest::class);
        });

        it('returns null for routes without validation rules', function (): void {
            RouteFacade::delete('/test/articles/{id}', [E2EController::class, 'delete']);
            $route = RouteFacade::getRoutes()->getByAction(E2EController::class . '@delete');

            $detector = app(ValidationRulesDetector::class);
            $result = $detector->detect($route, E2EController::class, 'delete');

            expect($result)->toBeNull();
        });
    });

    describe(class_basename(ValidationRulesSchemaBuilder::class), function (): void {
        it('builds request body schema from POST route with FormRequest', function (): void {
            RouteFacade::post('/test/articles', [E2EController::class, 'store']);
            $route = RouteFacade::getRoutes()->getByAction(E2EController::class . '@store');

            $builder = new ValidationRulesSchemaBuilder();
            $result = $builder->build(null, $route);

            expect($result)->toBeInstanceOf(RequestSchemaResult::class)
                ->and($result->target)->toBe(RequestTarget::BODY)
                ->and($result->encoding)->toBe(ContentEncoding::JSON);

            $compiled = $result->schema->compile();

            expect($compiled)->toHaveKey('properties')
                ->and($compiled['properties'])->toHaveKeys(['title', 'body', 'status', 'notify']);
        });

        it('sets target to QUERY for GET routes', function (): void {
            RouteFacade::get('/test/search', [E2EController::class, 'store']);
            $route = RouteFacade::getRoutes()->getByAction(E2EController::class . '@store');

            $builder = new ValidationRulesSchemaBuilder();
            $result = $builder->build(null, $route);

            expect($result->target)->toBe(RequestTarget::QUERY);
        });

        it('sets target to QUERY for DELETE routes', function (): void {
            RouteFacade::delete('/test/items/{id}', [E2EController::class, 'store']);
            $route = RouteFacade::getRoutes()->getByAction(E2EController::class . '@store');

            $builder = new ValidationRulesSchemaBuilder();
            $result = $builder->build(null, $route);

            expect($result->target)->toBe(RequestTarget::QUERY);
        });
    });
})->covers(ValidationRulesDetector::class, ValidationRulesSchemaBuilder::class);
