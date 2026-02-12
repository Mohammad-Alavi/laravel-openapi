<?php

use MohammadAlavi\Laragen\ModelSchema\ModelSchemaInferrer;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use Tests\Laragen\Support\Doubles\Models\BasicModel;
use Tests\Laragen\Support\Doubles\Models\DateCastModel;
use Tests\Laragen\Support\Doubles\Models\EnumCastModel;

describe(class_basename(ModelSchemaInferrer::class), function (): void {
    it('generates schema from model casts', function (): void {
        $inferrer = new ModelSchemaInferrer();
        $schema = $inferrer->infer(BasicModel::class);

        expect($schema)->toBeInstanceOf(JSONSchema::class);

        $compiled = $schema->compile();

        expect($compiled['type'])->toBe('object')
            ->and($compiled['properties'])->toHaveKeys(['name', 'age', 'score', 'is_active', 'metadata'])
            ->and($compiled['properties']['name']['type'])->toBe('string')
            ->and($compiled['properties']['age']['type'])->toBe('integer')
            ->and($compiled['properties']['score']['type'])->toBe('number')
            ->and($compiled['properties']['is_active']['type'])->toBe('boolean')
            ->and($compiled['properties']['metadata']['type'])->toBe('object');
    });

    it('excludes hidden fields', function (): void {
        $inferrer = new ModelSchemaInferrer();
        $schema = $inferrer->infer(BasicModel::class);
        $compiled = $schema->compile();

        expect($compiled['properties'])->not->toHaveKeys(['password', 'remember_token']);
    });

    it('includes appended fields as string', function (): void {
        $inferrer = new ModelSchemaInferrer();
        $schema = $inferrer->infer(BasicModel::class);
        $compiled = $schema->compile();

        expect($compiled['properties'])->toHaveKey('full_name')
            ->and($compiled['properties']['full_name']['type'])->toBe('string');
    });

    it('generates date-time format for date casts', function (): void {
        $inferrer = new ModelSchemaInferrer();
        $schema = $inferrer->infer(DateCastModel::class);
        $compiled = $schema->compile();

        expect($compiled['properties']['published_at']['format'])->toBe('date-time')
            ->and($compiled['properties']['created_date']['format'])->toBe('date-time')
            ->and($compiled['properties']['expires_at']['format'])->toBe('date-time')
            ->and($compiled['properties']['birth_date']['format'])->toBe('date-time');
    });

    it('generates integer for timestamp cast', function (): void {
        $inferrer = new ModelSchemaInferrer();
        $schema = $inferrer->infer(DateCastModel::class);
        $compiled = $schema->compile();

        expect($compiled['properties']['unix_time']['type'])->toBe('integer');
    });

    it('generates string for decimal cast', function (): void {
        $inferrer = new ModelSchemaInferrer();
        $schema = $inferrer->infer(DateCastModel::class);
        $compiled = $schema->compile();

        expect($compiled['properties']['price']['type'])->toBe('string');
    });

    it('generates enum from backed enum cast', function (): void {
        $inferrer = new ModelSchemaInferrer();
        $schema = $inferrer->infer(EnumCastModel::class);
        $compiled = $schema->compile();

        expect($compiled['properties']['status'])->toHaveKey('enum')
            ->and($compiled['properties']['status']['enum'])->toBe(['active', 'inactive', 'pending']);
    });

    it('always includes id field', function (): void {
        $inferrer = new ModelSchemaInferrer();
        $schema = $inferrer->infer(BasicModel::class);
        $compiled = $schema->compile();

        expect($compiled['properties'])->toHaveKey('id');
    });
})->covers(ModelSchemaInferrer::class);
