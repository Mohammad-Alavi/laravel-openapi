<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\ResponseSchema\EloquentModel\EloquentModelDetector;
use Tests\Laragen\Support\Doubles\EloquentController;
use Tests\Laragen\Support\Doubles\Models\BasicModel;
use Tests\Laragen\Support\Doubles\ResourceController;

describe(class_basename(EloquentModelDetector::class), function (): void {
    it('detects Model subclass return type', function (): void {
        $detector = new EloquentModelDetector();

        $result = $detector->detect(EloquentController::class, 'show');

        expect($result)->toBe(BasicModel::class);
    });

    it('returns null for non-existent method', function (): void {
        $detector = new EloquentModelDetector();

        $result = $detector->detect(EloquentController::class, 'nonExistent');

        expect($result)->toBeNull();
    });

    it('returns null for method without return type', function (): void {
        $detector = new EloquentModelDetector();

        $result = $detector->detect(EloquentController::class, 'noReturn');

        expect($result)->toBeNull();
    });

    it('returns null for non-Model return type', function (): void {
        $detector = new EloquentModelDetector();

        $result = $detector->detect(EloquentController::class, 'stringReturn');

        expect($result)->toBeNull();
    });

    it('returns null for JsonResource return type', function (): void {
        $detector = new EloquentModelDetector();

        $result = $detector->detect(ResourceController::class, 'show');

        expect($result)->toBeNull();
    });
})->covers(EloquentModelDetector::class);
