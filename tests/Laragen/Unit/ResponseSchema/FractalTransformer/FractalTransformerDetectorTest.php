<?php

use MohammadAlavi\Laragen\ResponseSchema\FractalTransformer\FractalTransformerDetector;
use Tests\Laragen\Support\Doubles\FractalController;
use Tests\Laragen\Support\Doubles\ResourceController;
use Tests\Laragen\Support\Doubles\Transformers\BookTransformer;

describe(class_basename(FractalTransformerDetector::class), function (): void {
    it('detects transformer instantiated with new', function (): void {
        $detector = new FractalTransformerDetector();

        $result = $detector->detect(FractalController::class, 'show');

        expect($result)->toBe(BookTransformer::class);
    });

    it('detects transformer referenced via ::class constant', function (): void {
        $detector = new FractalTransformerDetector();

        $result = $detector->detect(FractalController::class, 'showWithClassConst');

        expect($result)->toBe(BookTransformer::class);
    });

    it('returns null when no transformer found', function (): void {
        $detector = new FractalTransformerDetector();

        $result = $detector->detect(FractalController::class, 'noTransformer');

        expect($result)->toBeNull();
    });

    it('returns null for non-existent method', function (): void {
        $detector = new FractalTransformerDetector();

        $result = $detector->detect(FractalController::class, 'nonExistent');

        expect($result)->toBeNull();
    });

    it('returns null for controller returning JsonResource', function (): void {
        $detector = new FractalTransformerDetector();

        $result = $detector->detect(ResourceController::class, 'show');

        expect($result)->toBeNull();
    });
})->covers(FractalTransformerDetector::class);
