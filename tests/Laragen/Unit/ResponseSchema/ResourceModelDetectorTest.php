<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\ResponseSchema\ResourceModelDetector;
use Tests\Laragen\Support\Doubles\Models\BasicModel;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithMixin;
use Tests\Laragen\Support\Doubles\Resources\UserResource;

describe(class_basename(ResourceModelDetector::class), function (): void {
    it('detects model class from @mixin DocBlock', function (): void {
        $detector = new ResourceModelDetector();

        $result = $detector->detect(ResourceWithMixin::class);

        expect($result)->toBe(BasicModel::class);
    });

    it('returns null when resource has no @mixin annotation', function (): void {
        $detector = new ResourceModelDetector();

        $result = $detector->detect(UserResource::class);

        expect($result)->toBeNull();
    });
})->covers(ResourceModelDetector::class);
