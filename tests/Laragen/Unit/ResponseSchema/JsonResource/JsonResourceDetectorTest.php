<?php

use MohammadAlavi\Laragen\ResponseSchema\JsonResource\JsonResourceDetector;
use Tests\Laragen\Support\Doubles\ResourceController;
use Tests\Laragen\Support\Doubles\Resources\PostResource;
use Tests\Laragen\Support\Doubles\Resources\UserResource;

describe(class_basename(JsonResourceDetector::class), function (): void {
    it('detects JsonResource return type from controller method', function (): void {
        $detector = new JsonResourceDetector();
        $resourceClass = $detector->detect(ResourceController::class, 'show');

        expect($resourceClass)->toBe(UserResource::class);
    });

    it('returns null when no JsonResource return type', function (): void {
        $detector = new JsonResourceDetector();
        $resourceClass = $detector->detect(ResourceController::class, 'noReturn');

        expect($resourceClass)->toBeNull();
    });

    it('detects different resource types', function (): void {
        $detector = new JsonResourceDetector();
        $resourceClass = $detector->detect(ResourceController::class, 'showPost');

        expect($resourceClass)->toBe(PostResource::class);
    });
})->covers(JsonResourceDetector::class);
