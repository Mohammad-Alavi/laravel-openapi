<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\ResponseSchema\ResponseDetector;
use Tests\Laragen\Support\Doubles\ResourceController;
use Tests\Laragen\Support\Doubles\Resources\PostResource;
use Tests\Laragen\Support\Doubles\Resources\UserResource;

describe(class_basename(ResponseDetector::class), function (): void {
    it('detects JsonResource return type from controller method', function (): void {
        $detector = new ResponseDetector();
        $resourceClass = $detector->detect(ResourceController::class, 'show');

        expect($resourceClass)->toBe(UserResource::class);
    });

    it('returns null when no JsonResource return type', function (): void {
        $detector = new ResponseDetector();
        $resourceClass = $detector->detect(ResourceController::class, 'noReturn');

        expect($resourceClass)->toBeNull();
    });

    it('detects different resource types', function (): void {
        $detector = new ResponseDetector();
        $resourceClass = $detector->detect(ResourceController::class, 'showPost');

        expect($resourceClass)->toBe(PostResource::class);
    });
})->covers(ResponseDetector::class);
