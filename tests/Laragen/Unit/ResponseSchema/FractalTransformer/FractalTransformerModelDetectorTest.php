<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\ResponseSchema\FractalTransformer\FractalTransformerModelDetector;
use Tests\Laragen\Support\Doubles\Models\BasicModel;
use Tests\Laragen\Support\Doubles\Transformers\AuthorTransformer;
use Tests\Laragen\Support\Doubles\Transformers\BookTransformer;

describe(class_basename(FractalTransformerModelDetector::class), function (): void {
    it('resolves Model class from transform parameter type', function (): void {
        $detector = new FractalTransformerModelDetector();

        $result = $detector->detect(BookTransformer::class);

        expect($result)->toBe(BasicModel::class);
    });

    it('returns null when transform parameter is not a Model', function (): void {
        $detector = new FractalTransformerModelDetector();

        $result = $detector->detect(AuthorTransformer::class);

        expect($result)->toBeNull();
    });
})->covers(FractalTransformerModelDetector::class);
