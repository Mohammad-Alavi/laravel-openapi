<?php

use MohammadAlavi\Laragen\ResponseSchema\SpatieData\SpatieDataDetector;
use Tests\Laragen\Support\Doubles\DataObjects\UserData;
use Tests\Laragen\Support\Doubles\ResourceController;
use Tests\Laragen\Support\Doubles\SpatieDataController;

describe(class_basename(SpatieDataDetector::class), function (): void {
    it('detects Spatie Data return type from controller method', function (): void {
        $detector = new SpatieDataDetector();

        $result = $detector->detect(SpatieDataController::class, 'show');

        expect($result)->toBe(UserData::class);
    });

    it('returns null for non-Data return type', function (): void {
        $detector = new SpatieDataDetector();

        $result = $detector->detect(SpatieDataController::class, 'stringReturn');

        expect($result)->toBeNull();
    });

    it('returns null for method without return type', function (): void {
        $detector = new SpatieDataDetector();

        $result = $detector->detect(SpatieDataController::class, 'noReturn');

        expect($result)->toBeNull();
    });

    it('returns null for non-existent method', function (): void {
        $detector = new SpatieDataDetector();

        $result = $detector->detect(SpatieDataController::class, 'nonExistent');

        expect($result)->toBeNull();
    });

    it('returns null for JsonResource return type', function (): void {
        $detector = new SpatieDataDetector();

        $result = $detector->detect(ResourceController::class, 'show');

        expect($result)->toBeNull();
    });
})->covers(SpatieDataDetector::class);
