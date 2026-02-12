<?php

use MohammadAlavi\Laragen\ModelSchema\ModelSchemaInferrer;
use MohammadAlavi\Laragen\ResponseSchema\EloquentModel\EloquentModelSchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use Tests\Laragen\Support\Doubles\Models\BasicModel;

describe(class_basename(EloquentModelSchemaBuilder::class), function (): void {
    it('builds schema from Model class using ModelSchemaInferrer', function (): void {
        $builder = new EloquentModelSchemaBuilder(new ModelSchemaInferrer());

        $schema = $builder->build(BasicModel::class);

        expect($schema)->toBeInstanceOf(JSONSchema::class);

        $compiled = $schema->compile();

        expect($compiled['type'])->toBe('object')
            ->and($compiled['properties'])->toHaveKey('id')
            ->and($compiled['properties']['id']['type'])->toBe('integer')
            ->and($compiled['properties'])->toHaveKey('name')
            ->and($compiled['properties']['name']['type'])->toBe('string');
    });
})->covers(EloquentModelSchemaBuilder::class);
