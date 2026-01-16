<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentSchemas\DependentSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\PatternProperties\PatternProperty;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;

describe('LooseFluentDescriptor new keyword methods', function (): void {
    it('can set prefixItems', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->prefixItems(
                LooseFluentDescriptor::withoutSchema()->type('string'),
                LooseFluentDescriptor::withoutSchema()->type('number'),
            );

        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('prefixItems')
            ->and($compiled['prefixItems'])->toHaveCount(2)
            ->and($compiled['prefixItems'][0]['type'])->toBe('string')
            ->and($compiled['prefixItems'][1]['type'])->toBe('number');
    });

    it('can set contains', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->contains(
                LooseFluentDescriptor::withoutSchema()->type('number')->minimum(5),
            );

        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('contains')
            ->and($compiled['contains']['type'])->toBe('number')
            ->and($compiled['contains']['minimum'])->toBe(5);
    });

    it('can set patternProperties', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->patternProperties(
                PatternProperty::create('^S_', LooseFluentDescriptor::withoutSchema()->type('string')),
                PatternProperty::create('^I_', LooseFluentDescriptor::withoutSchema()->type('integer')),
            );

        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('patternProperties')
            ->and($compiled['patternProperties']['^S_']['type'])->toBe('string')
            ->and($compiled['patternProperties']['^I_']['type'])->toBe('integer');
    });

    it('can set dependentSchemas', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->dependentSchemas(
                DependentSchema::create(
                    'credit_card',
                    LooseFluentDescriptor::withoutSchema()->required('billing_address'),
                ),
            );

        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('dependentSchemas')
            ->and($compiled['dependentSchemas']['credit_card']['required'])->toBe(['billing_address']);
    });

    it('can set propertyNames', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->propertyNames(
                LooseFluentDescriptor::withoutSchema()->pattern('^[a-z]+$'),
            );

        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('propertyNames')
            ->and($compiled['propertyNames']['pattern'])->toBe('^[a-z]+$');
    });

    it('can set contentEncoding', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->contentEncoding('base64');

        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('contentEncoding')
            ->and($compiled['contentEncoding'])->toBe('base64');
    });

    it('can set contentMediaType', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->contentMediaType('application/json');

        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('contentMediaType')
            ->and($compiled['contentMediaType'])->toBe('application/json');
    });

    it('can set contentSchema', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->contentSchema(
                LooseFluentDescriptor::withoutSchema()->type('object'),
            );

        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('contentSchema')
            ->and($compiled['contentSchema']['type'])->toBe('object');
    });

    it('can combine multiple content keywords', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->type('string')
            ->contentEncoding('base64')
            ->contentMediaType('application/json')
            ->contentSchema(
                LooseFluentDescriptor::withoutSchema()->type('object'),
            );

        $compiled = $schema->compile();

        expect($compiled['type'])->toBe('string')
            ->and($compiled['contentEncoding'])->toBe('base64')
            ->and($compiled['contentMediaType'])->toBe('application/json')
            ->and($compiled['contentSchema']['type'])->toBe('object');
    });

    it('can build tuple schema with prefixItems and items', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->type('array')
            ->prefixItems(
                LooseFluentDescriptor::withoutSchema()->type('string'),
                LooseFluentDescriptor::withoutSchema()->type('number'),
            )
            ->items(
                LooseFluentDescriptor::withoutSchema()->type('boolean'),
            );

        $compiled = $schema->compile();

        expect($compiled['type'])->toBe('array')
            ->and($compiled['prefixItems'])->toHaveCount(2)
            ->and($compiled['items']['type'])->toBe('boolean');
    });
})->covers(LooseFluentDescriptor::class);

describe('StrictFluentDescriptor new keyword methods', function (): void {
    it('can build array schema with contains', function (): void {
        $schema = StrictFluentDescriptor::array()
            ->contains(
                StrictFluentDescriptor::number()->minimum(5),
            )
            ->minContains(1)
            ->maxContains(3);

        $compiled = $schema->compile();

        expect($compiled['type'])->toBe('array')
            ->and($compiled['contains']['type'])->toBe('number')
            ->and($compiled['minContains'])->toBe(1)
            ->and($compiled['maxContains'])->toBe(3);
    });

    it('can build object schema with patternProperties', function (): void {
        $schema = StrictFluentDescriptor::object()
            ->patternProperties(
                PatternProperty::create('^str_', StrictFluentDescriptor::string()),
            );

        $compiled = $schema->compile();

        expect($compiled['type'])->toBe('object')
            ->and($compiled['patternProperties'])->toHaveKey('^str_');
    });

    it('can build string schema with content keywords', function (): void {
        $schema = StrictFluentDescriptor::string()
            ->contentEncoding('base64')
            ->contentMediaType('image/png');

        $compiled = $schema->compile();

        expect($compiled['type'])->toBe('string')
            ->and($compiled['contentEncoding'])->toBe('base64')
            ->and($compiled['contentMediaType'])->toBe('image/png');
    });
})->covers(LooseFluentDescriptor::class);
