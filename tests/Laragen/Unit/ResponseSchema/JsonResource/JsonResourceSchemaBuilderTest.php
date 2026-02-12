<?php

use MohammadAlavi\Laragen\ModelSchema\ModelSchemaInferrer;
use MohammadAlavi\Laragen\ResponseSchema\ArraySchema\ArraySchemaAnalyzer;
use MohammadAlavi\Laragen\ResponseSchema\JsonResource\JsonResourceModelDetector;
use MohammadAlavi\Laragen\ResponseSchema\JsonResource\JsonResourceSchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithCollection;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithLiterals;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithMerge;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithMixin;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithNestedArray;
use Tests\Laragen\Support\Doubles\Resources\UnwrappedResource;
use Tests\Laragen\Support\Doubles\Resources\UserResource;

describe(class_basename(JsonResourceSchemaBuilder::class), function (): void {
    it('builds schema from resource fields', function (): void {
        $builder = new JsonResourceSchemaBuilder(
            new ArraySchemaAnalyzer(),
            new ModelSchemaInferrer(),
            new JsonResourceModelDetector(),
        );

        $schema = $builder->build(UserResource::class);

        expect($schema)->toBeInstanceOf(JSONSchema::class);

        $compiled = $schema->compile();

        // Default data wrapper
        expect($compiled['type'])->toBe('object')
            ->and($compiled['properties'])->toHaveKey('data');

        $dataSchema = $compiled['properties']['data'];

        expect($dataSchema['type'])->toBe('object')
            ->and($dataSchema['properties'])->toHaveKeys(['id', 'name', 'email', 'type', 'is_active']);
    });

    it('builds unwrapped schema when wrap is null', function (): void {
        $builder = new JsonResourceSchemaBuilder(
            new ArraySchemaAnalyzer(),
            new ModelSchemaInferrer(),
            new JsonResourceModelDetector(),
        );

        $schema = $builder->build(UnwrappedResource::class);
        $compiled = $schema->compile();

        expect($compiled['type'])->toBe('object')
            ->and($compiled['properties'])->toHaveKeys(['id', 'name'])
            ->and($compiled['properties'])->not->toHaveKey('data');
    });

    it('generates string type for model property fields without model context', function (): void {
        $builder = new JsonResourceSchemaBuilder(
            new ArraySchemaAnalyzer(),
            new ModelSchemaInferrer(),
            new JsonResourceModelDetector(),
        );

        $schema = $builder->build(UserResource::class);
        $compiled = $schema->compile();
        $dataProps = $compiled['properties']['data']['properties'];

        // Model properties default to string when no @mixin annotation
        expect($dataProps['name']['type'])->toBe('string');
    });

    it('generates const value for literal fields', function (): void {
        $builder = new JsonResourceSchemaBuilder(
            new ArraySchemaAnalyzer(),
            new ModelSchemaInferrer(),
            new JsonResourceModelDetector(),
        );

        $schema = $builder->build(UserResource::class);
        $compiled = $schema->compile();
        $dataProps = $compiled['properties']['data']['properties'];

        expect($dataProps['type']['enum'])->toBe(['user']);
    });

    it('generates enum schema for boolean and null literal fields', function (): void {
        $builder = new JsonResourceSchemaBuilder(
            new ArraySchemaAnalyzer(),
            new ModelSchemaInferrer(),
            new JsonResourceModelDetector(),
        );

        $schema = $builder->build(ResourceWithLiterals::class);
        $compiled = $schema->compile();
        $dataProps = $compiled['properties']['data']['properties'];

        expect($dataProps['is_verified']['enum'])->toBe([true])
            ->and($dataProps['is_banned']['enum'])->toBe([false])
            ->and($dataProps['deleted_at']['enum'])->toBe([null]);
    });

    it('generates array schema with items for Resource::collection()', function (): void {
        $builder = new JsonResourceSchemaBuilder(
            new ArraySchemaAnalyzer(),
            new ModelSchemaInferrer(),
            new JsonResourceModelDetector(),
        );

        $schema = $builder->build(ResourceWithCollection::class);
        $compiled = $schema->compile();
        $dataProps = $compiled['properties']['data']['properties'];

        expect($dataProps['posts']['type'])->toBe('array')
            ->and($dataProps['posts']['items']['type'])->toBe('object')
            ->and($dataProps['posts']['items']['properties'])->toHaveKeys(['id', 'title', 'author', 'tags']);
    });

    it('includes merged fields in schema output', function (): void {
        $builder = new JsonResourceSchemaBuilder(
            new ArraySchemaAnalyzer(),
            new ModelSchemaInferrer(),
            new JsonResourceModelDetector(),
        );

        $schema = $builder->build(ResourceWithMerge::class);
        $compiled = $schema->compile();
        $dataProps = $compiled['properties']['data']['properties'];

        expect($dataProps)->toHaveKeys(['id', 'first_name', 'last_name', 'role', 'permissions', 'settings']);
    });

    it('builds nested object schema for inline array literals', function (): void {
        $builder = new JsonResourceSchemaBuilder(
            new ArraySchemaAnalyzer(),
            new ModelSchemaInferrer(),
            new JsonResourceModelDetector(),
        );

        $schema = $builder->build(ResourceWithNestedArray::class);
        $compiled = $schema->compile();
        $dataProps = $compiled['properties']['data']['properties'];

        expect($dataProps['meta']['type'])->toBe('object')
            ->and($dataProps['meta']['properties'])->toHaveKeys(['created_at', 'version']);
    });

    it('infers model property types from model schema via @mixin', function (): void {
        $builder = new JsonResourceSchemaBuilder(
            new ArraySchemaAnalyzer(),
            new ModelSchemaInferrer(),
            new JsonResourceModelDetector(),
        );

        $schema = $builder->build(ResourceWithMixin::class);
        $compiled = $schema->compile();
        $dataProps = $compiled['properties']['data']['properties'];

        // BasicModel has integer key type (default) → id should be integer
        expect($dataProps['id']['type'])->toBe('integer')
            // BasicModel has 'name' => 'string' cast → should be string
            ->and($dataProps['name']['type'])->toBe('string');
    });
})->covers(JsonResourceSchemaBuilder::class);
