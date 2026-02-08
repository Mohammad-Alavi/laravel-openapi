<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\ArraySchema\ArraySchemaAnalyzer;
use MohammadAlavi\Laragen\ModelSchema\ModelSchemaInferrer;
use MohammadAlavi\Laragen\ResponseSchema\FractalTransformer\FractalTransformerModelDetector;
use MohammadAlavi\Laragen\ResponseSchema\FractalTransformer\FractalTransformerSchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use Tests\Laragen\Support\Doubles\Transformers\AuthorTransformer;
use Tests\Laragen\Support\Doubles\Transformers\BookTransformer;

describe(class_basename(FractalTransformerSchemaBuilder::class), function (): void {
    it('builds schema from transformer with model type hint', function (): void {
        $builder = new FractalTransformerSchemaBuilder(
            new ArraySchemaAnalyzer(),
            new ModelSchemaInferrer(),
            new FractalTransformerModelDetector(),
        );

        $schema = $builder->build(BookTransformer::class);

        expect($schema)->toBeInstanceOf(JSONSchema::class);

        $compiled = $schema->compile();

        expect($compiled['type'])->toBe('object')
            ->and($compiled['properties'])->toHaveKeys(['id', 'title', 'is_published']);
    });

    it('builds schema from transformer without model type hint', function (): void {
        $builder = new FractalTransformerSchemaBuilder(
            new ArraySchemaAnalyzer(),
            new ModelSchemaInferrer(),
            new FractalTransformerModelDetector(),
        );

        $schema = $builder->build(AuthorTransformer::class);

        expect($schema)->toBeInstanceOf(JSONSchema::class);

        $compiled = $schema->compile();

        expect($compiled['type'])->toBe('object')
            ->and($compiled['properties'])->toHaveKeys(['name', 'bio']);
    });
})->covers(FractalTransformerSchemaBuilder::class);
