<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\ResponseSchema\JsonResourceAnalyzer;
use MohammadAlavi\Laragen\ResponseSchema\ResponseSchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use Tests\Laragen\Support\Doubles\Resources\UnwrappedResource;
use Tests\Laragen\Support\Doubles\Resources\UserResource;

describe(class_basename(ResponseSchemaBuilder::class), function (): void {
    it('builds schema from resource fields', function (): void {
        $analyzer = new JsonResourceAnalyzer();
        $builder = new ResponseSchemaBuilder($analyzer);

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
        $analyzer = new JsonResourceAnalyzer();
        $builder = new ResponseSchemaBuilder($analyzer);

        $schema = $builder->build(UnwrappedResource::class);
        $compiled = $schema->compile();

        expect($compiled['type'])->toBe('object')
            ->and($compiled['properties'])->toHaveKeys(['id', 'name'])
            ->and($compiled['properties'])->not->toHaveKey('data');
    });

    it('generates string type for model property fields', function (): void {
        $analyzer = new JsonResourceAnalyzer();
        $builder = new ResponseSchemaBuilder($analyzer);

        $schema = $builder->build(UserResource::class);
        $compiled = $schema->compile();
        $dataProps = $compiled['properties']['data']['properties'];

        // Model properties default to string when no model context
        expect($dataProps['name']['type'])->toBe('string');
    });

    it('generates const value for literal fields', function (): void {
        $analyzer = new JsonResourceAnalyzer();
        $builder = new ResponseSchemaBuilder($analyzer);

        $schema = $builder->build(UserResource::class);
        $compiled = $schema->compile();
        $dataProps = $compiled['properties']['data']['properties'];

        expect($dataProps['type']['enum'])->toBe(['user']);
    });
})->covers(ResponseSchemaBuilder::class);
