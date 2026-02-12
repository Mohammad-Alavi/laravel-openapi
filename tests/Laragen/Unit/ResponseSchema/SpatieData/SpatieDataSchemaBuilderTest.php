<?php

use MohammadAlavi\Laragen\ResponseSchema\SpatieData\SpatieDataSchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use Tests\Laragen\Support\Doubles\DataObjects\AddressData;
use Tests\Laragen\Support\Doubles\DataObjects\UserData;

describe(class_basename(SpatieDataSchemaBuilder::class), function (): void {
    it('builds schema with basic types from constructor parameters', function (): void {
        $builder = new SpatieDataSchemaBuilder();

        $schema = $builder->build(AddressData::class);

        expect($schema)->toBeInstanceOf(JSONSchema::class);

        $compiled = $schema->compile();

        expect($compiled['type'])->toBe('object')
            ->and($compiled['properties'])->toHaveKeys(['street', 'city', 'zip'])
            ->and($compiled['properties']['street']['type'])->toBe('string')
            ->and($compiled['properties']['city']['type'])->toBe('string')
            ->and($compiled['properties']['zip']['type'])->toBe('string')
            ->and($compiled['required'])->toBe(['street', 'city', 'zip']);
    });

    it('builds schema with all supported types', function (): void {
        $builder = new SpatieDataSchemaBuilder();

        $schema = $builder->build(UserData::class);
        $compiled = $schema->compile();

        expect($compiled['type'])->toBe('object')
            ->and($compiled['properties'])->toHaveKeys([
                'name', 'age', 'score', 'is_active', 'nickname',
                'address', 'status', 'created_at', 'addresses', 'bio',
            ]);

        // Basic types
        expect($compiled['properties']['name']['type'])->toBe('string')
            ->and($compiled['properties']['age']['type'])->toBe('integer')
            ->and($compiled['properties']['score']['type'])->toBe('number')
            ->and($compiled['properties']['is_active']['type'])->toBe('boolean');
    });

    it('handles nullable properties', function (): void {
        $builder = new SpatieDataSchemaBuilder();

        $schema = $builder->build(UserData::class);
        $compiled = $schema->compile();

        // ?string generates anyOf with string and null
        $nickname = $compiled['properties']['nickname'];

        expect($nickname)->toHaveKey('anyOf')
            ->and($nickname['anyOf'])->toHaveCount(2)
            ->and($nickname['anyOf'][0]['type'])->toBe('string')
            ->and($nickname['anyOf'][1]['type'])->toBe('null');
    });

    it('handles nested Data objects recursively', function (): void {
        $builder = new SpatieDataSchemaBuilder();

        $schema = $builder->build(UserData::class);
        $compiled = $schema->compile();

        $address = $compiled['properties']['address'];

        expect($address['type'])->toBe('object')
            ->and($address['properties'])->toHaveKeys(['street', 'city', 'zip']);
    });

    it('handles BackedEnum properties', function (): void {
        $builder = new SpatieDataSchemaBuilder();

        $schema = $builder->build(UserData::class);
        $compiled = $schema->compile();

        $status = $compiled['properties']['status'];

        expect($status['enum'])->toBe(['active', 'inactive', 'banned']);
    });

    it('handles DateTime/Carbon properties as date-time format', function (): void {
        $builder = new SpatieDataSchemaBuilder();

        $schema = $builder->build(UserData::class);
        $compiled = $schema->compile();

        $createdAt = $compiled['properties']['created_at'];

        expect($createdAt['type'])->toBe('string')
            ->and($createdAt['format'])->toBe('date-time');
    });

    it('handles DataCollectionOf attribute for typed collections', function (): void {
        $builder = new SpatieDataSchemaBuilder();

        $schema = $builder->build(UserData::class);
        $compiled = $schema->compile();

        $addresses = $compiled['properties']['addresses'];

        expect($addresses['type'])->toBe('array')
            ->and($addresses['items']['type'])->toBe('object')
            ->and($addresses['items']['properties'])->toHaveKeys(['street', 'city', 'zip']);
    });

    it('excludes Optional union type from required array', function (): void {
        $builder = new SpatieDataSchemaBuilder();

        $schema = $builder->build(UserData::class);
        $compiled = $schema->compile();

        expect($compiled['required'])->toContain('name', 'age', 'score', 'is_active', 'address', 'status', 'created_at', 'addresses')
            ->and($compiled['required'])->not->toContain('bio', 'nickname');
    });
})->covers(SpatieDataSchemaBuilder::class);
