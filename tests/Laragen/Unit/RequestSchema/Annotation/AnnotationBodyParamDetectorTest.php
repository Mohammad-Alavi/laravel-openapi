<?php

declare(strict_types=1);

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\Annotations\DetectedBodyParam;
use MohammadAlavi\Laragen\RequestSchema\Annotation\AnnotationBodyParamDetector;
use Tests\Laragen\Support\Doubles\Annotations\AnnotatedController;

describe(class_basename(AnnotationBodyParamDetector::class), function (): void {
    it('detects @bodyParam annotations and returns DetectedBodyParam array', function (): void {
        $detector = new AnnotationBodyParamDetector();
        $route = new Route('POST', '/test', []);

        $result = $detector->detect($route, AnnotatedController::class, 'withBodyParams');

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(3)
            ->and($result[0])->toBeInstanceOf(DetectedBodyParam::class);
    });

    it('returns null when no @bodyParam annotations exist', function (): void {
        $detector = new AnnotationBodyParamDetector();
        $route = new Route('POST', '/test', []);

        $result = $detector->detect($route, AnnotatedController::class, 'withoutAnnotations');

        expect($result)->toBeNull();
    });

    it('returns null for non-existent method', function (): void {
        $detector = new AnnotationBodyParamDetector();
        $route = new Route('POST', '/test', []);

        $result = $detector->detect($route, AnnotatedController::class, 'nonExistent');

        expect($result)->toBeNull();
    });

    it('returns null for method with only non-bodyParam annotations', function (): void {
        $detector = new AnnotationBodyParamDetector();
        $route = new Route('POST', '/test', []);

        $result = $detector->detect($route, AnnotatedController::class, 'withQueryParams');

        expect($result)->toBeNull();
    });
})->covers(AnnotationBodyParamDetector::class);
