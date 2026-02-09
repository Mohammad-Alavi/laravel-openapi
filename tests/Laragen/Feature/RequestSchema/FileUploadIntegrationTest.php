<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route as RouteFacade;
use MohammadAlavi\Laragen\RequestSchema\ContentEncoding;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResolver;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResult;
use MohammadAlavi\Laragen\RequestSchema\RequestTarget;
use MohammadAlavi\Laragen\RequestSchema\ValidationRules\ValidationRulesDetector;
use MohammadAlavi\Laragen\RequestSchema\ValidationRules\ValidationRulesSchemaBuilder;
use Tests\Laragen\Support\Doubles\E2E\E2EController;

describe('File upload integration', function (): void {
    it('detects multipart encoding when route has file validation rules', function (): void {
        RouteFacade::post('/test/upload', [E2EController::class, 'upload']);
        $route = RouteFacade::getRoutes()->getByAction(E2EController::class . '@upload');

        $detector = app(ValidationRulesDetector::class);
        $detected = $detector->detect($route, E2EController::class, 'upload');

        $builder = new ValidationRulesSchemaBuilder();
        $result = $builder->build($detected, $route);

        expect($result)->toBeInstanceOf(RequestSchemaResult::class)
            ->and($result->target)->toBe(RequestTarget::BODY)
            ->and($result->encoding)->toBe(ContentEncoding::MULTIPART_FORM_DATA);

        $compiled = $result->schema->compile();

        expect($compiled['properties'])->toHaveKey('avatar');
    });

    it('uses JSON encoding when route has no file rules', function (): void {
        RouteFacade::post('/test/articles', [E2EController::class, 'store']);
        $route = RouteFacade::getRoutes()->getByAction(E2EController::class . '@store');

        $detector = app(ValidationRulesDetector::class);
        $detected = $detector->detect($route, E2EController::class, 'store');

        $builder = new ValidationRulesSchemaBuilder();
        $result = $builder->build($detected, $route);

        expect($result)->toBeInstanceOf(RequestSchemaResult::class)
            ->and($result->encoding)->toBe(ContentEncoding::JSON);
    });

    it('produces multipart/form-data in full resolver pipeline', function (): void {
        RouteFacade::post('/test/upload-full', [E2EController::class, 'upload']);
        $route = RouteFacade::getRoutes()->getByAction(E2EController::class . '@upload');

        $resolver = app(RequestSchemaResolver::class);
        $result = $resolver->resolve($route, E2EController::class, 'upload');

        expect($result)->toBeInstanceOf(RequestSchemaResult::class)
            ->and($result->encoding)->toBe(ContentEncoding::MULTIPART_FORM_DATA)
            ->and($result->target)->toBe(RequestTarget::BODY);
    });
})->covers(ValidationRulesSchemaBuilder::class);
