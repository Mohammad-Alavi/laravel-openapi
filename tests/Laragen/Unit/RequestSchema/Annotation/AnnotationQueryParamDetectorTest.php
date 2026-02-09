<?php

declare(strict_types=1);

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\Annotations\DetectedQueryParam;
use MohammadAlavi\Laragen\RequestSchema\Annotation\AnnotationQueryParamDetector;
use Tests\Laragen\Support\Doubles\Annotations\AnnotatedController;

describe(class_basename(AnnotationQueryParamDetector::class), function (): void {
    it('detects @queryParam annotations and returns DetectedQueryParam array', function (): void {
        $detector = new AnnotationQueryParamDetector();
        $route = new Route('GET', '/test', []);

        $result = $detector->detect($route, AnnotatedController::class, 'withQueryParams');

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(3)
            ->and($result[0])->toBeInstanceOf(DetectedQueryParam::class);
    });

    it('returns null when no @queryParam annotations exist', function (): void {
        $detector = new AnnotationQueryParamDetector();
        $route = new Route('GET', '/test', []);

        $result = $detector->detect($route, AnnotatedController::class, 'withoutAnnotations');

        expect($result)->toBeNull();
    });

    it('returns null for non-existent method', function (): void {
        $detector = new AnnotationQueryParamDetector();
        $route = new Route('GET', '/test', []);

        $result = $detector->detect($route, AnnotatedController::class, 'nonExistent');

        expect($result)->toBeNull();
    });

    it('returns null for method with only non-queryParam annotations', function (): void {
        $detector = new AnnotationQueryParamDetector();
        $route = new Route('GET', '/test', []);

        $result = $detector->detect($route, AnnotatedController::class, 'withBodyParams');

        expect($result)->toBeNull();
    });
})->covers(AnnotationQueryParamDetector::class);
