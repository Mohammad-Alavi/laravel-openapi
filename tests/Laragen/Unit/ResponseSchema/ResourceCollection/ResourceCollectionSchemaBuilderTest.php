<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\ArraySchema\ArraySchemaAnalyzer;
use MohammadAlavi\Laragen\ModelSchema\ModelSchemaInferrer;
use MohammadAlavi\Laragen\ResponseSchema\JsonResource\JsonResourceModelDetector;
use MohammadAlavi\Laragen\ResponseSchema\JsonResource\JsonResourceSchemaBuilder;
use MohammadAlavi\Laragen\ResponseSchema\ResourceCollection\ResourceCollectionSchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use Tests\Laragen\Support\Doubles\Collections\CustomWrapCollection;
use Tests\Laragen\Support\Doubles\Collections\UserCollection;
use Tests\Laragen\Support\Doubles\Resources\PostCollection;

describe(class_basename(ResourceCollectionSchemaBuilder::class), function (): void {
    it('builds array schema wrapping inner resource with explicit collects', function (): void {
        $jsonResourceBuilder = new JsonResourceSchemaBuilder(
            new ArraySchemaAnalyzer(),
            new ModelSchemaInferrer(),
            new JsonResourceModelDetector(),
        );
        $builder = new ResourceCollectionSchemaBuilder($jsonResourceBuilder);

        $schema = $builder->build(UserCollection::class);

        expect($schema)->toBeInstanceOf(JSONSchema::class);

        $compiled = $schema->compile();

        // Default 'data' wrapper
        expect($compiled['type'])->toBe('object')
            ->and($compiled['properties'])->toHaveKey('data');

        $dataSchema = $compiled['properties']['data'];

        expect($dataSchema['type'])->toBe('array')
            ->and($dataSchema['items']['type'])->toBe('object')
            ->and($dataSchema['items']['properties'])->toHaveKeys(['id', 'name', 'email', 'type', 'is_active']);
    });

    it('builds array schema with conventional name resolution', function (): void {
        $jsonResourceBuilder = new JsonResourceSchemaBuilder(
            new ArraySchemaAnalyzer(),
            new ModelSchemaInferrer(),
            new JsonResourceModelDetector(),
        );
        $builder = new ResourceCollectionSchemaBuilder($jsonResourceBuilder);

        $schema = $builder->build(PostCollection::class);
        $compiled = $schema->compile();

        // Default 'data' wrapper
        expect($compiled['type'])->toBe('object')
            ->and($compiled['properties'])->toHaveKey('data');

        $dataSchema = $compiled['properties']['data'];

        expect($dataSchema['type'])->toBe('array')
            ->and($dataSchema['items']['type'])->toBe('object')
            ->and($dataSchema['items']['properties'])->toHaveKeys(['id', 'title', 'author', 'tags']);
    });

    it('respects custom wrap property on collection', function (): void {
        $jsonResourceBuilder = new JsonResourceSchemaBuilder(
            new ArraySchemaAnalyzer(),
            new ModelSchemaInferrer(),
            new JsonResourceModelDetector(),
        );
        $builder = new ResourceCollectionSchemaBuilder($jsonResourceBuilder);

        $schema = $builder->build(CustomWrapCollection::class);
        $compiled = $schema->compile();

        expect($compiled['type'])->toBe('object')
            ->and($compiled['properties'])->toHaveKey('users')
            ->and($compiled['properties'])->not->toHaveKey('data');

        $usersSchema = $compiled['properties']['users'];

        expect($usersSchema['type'])->toBe('array')
            ->and($usersSchema['items']['type'])->toBe('object')
            ->and($usersSchema['items']['properties'])->toHaveKeys(['id', 'name', 'email', 'type', 'is_active']);
    });
})->covers(ResourceCollectionSchemaBuilder::class);
