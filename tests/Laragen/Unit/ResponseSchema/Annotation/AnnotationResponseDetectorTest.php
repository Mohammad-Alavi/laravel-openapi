<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\Annotations\DetectedResponseAnnotation;
use MohammadAlavi\Laragen\ResponseSchema\Annotation\AnnotationResponseDetector;
use Tests\Laragen\Support\Doubles\Annotations\AnnotatedController;

describe(class_basename(AnnotationResponseDetector::class), function (): void {
    it('detects @response annotations and returns DetectedResponseAnnotation array', function (): void {
        $detector = new AnnotationResponseDetector();

        $result = $detector->detect(AnnotatedController::class, 'withResponse');

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(1)
            ->and($result[0])->toBeInstanceOf(DetectedResponseAnnotation::class);
    });

    it('returns null when no @response annotations exist', function (): void {
        $detector = new AnnotationResponseDetector();

        $result = $detector->detect(AnnotatedController::class, 'withoutAnnotations');

        expect($result)->toBeNull();
    });

    it('returns null for non-existent method', function (): void {
        $detector = new AnnotationResponseDetector();

        $result = $detector->detect(AnnotatedController::class, 'nonExistent');

        expect($result)->toBeNull();
    });

    it('returns null for method with only non-response annotations', function (): void {
        $detector = new AnnotationResponseDetector();

        $result = $detector->detect(AnnotatedController::class, 'withQueryParams');

        expect($result)->toBeNull();
    });
})->covers(AnnotationResponseDetector::class);
