<?php

use MohammadAlavi\Laragen\ResponseSchema\ResourceCollection\ResourceCollectionDetector;
use Tests\Laragen\Support\Doubles\Collections\UserCollection;
use Tests\Laragen\Support\Doubles\ResourceCollectionController;

describe(class_basename(ResourceCollectionDetector::class), function (): void {
    it('detects ResourceCollection return type from controller method', function (): void {
        $detector = new ResourceCollectionDetector();

        $result = $detector->detect(ResourceCollectionController::class, 'index');

        expect($result)->toBe(UserCollection::class);
    });

    it('returns null for regular JsonResource return type', function (): void {
        $detector = new ResourceCollectionDetector();

        $result = $detector->detect(ResourceCollectionController::class, 'singleResource');

        expect($result)->toBeNull();
    });

    it('returns null for non-existent method', function (): void {
        $detector = new ResourceCollectionDetector();

        $result = $detector->detect(ResourceCollectionController::class, 'nonExistent');

        expect($result)->toBeNull();
    });

    it('returns null for method without return type', function (): void {
        $detector = new ResourceCollectionDetector();

        $result = $detector->detect(ResourceCollectionController::class, 'noReturn');

        expect($result)->toBeNull();
    });

    it('returns null for unrelated return type', function (): void {
        $detector = new ResourceCollectionDetector();

        $result = $detector->detect(ResourceCollectionController::class, 'stringReturn');

        expect($result)->toBeNull();
    });
})->covers(ResourceCollectionDetector::class);
