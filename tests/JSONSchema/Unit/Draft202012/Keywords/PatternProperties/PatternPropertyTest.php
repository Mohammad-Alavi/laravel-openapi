<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\PatternProperties\PatternProperty;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(PatternProperty::class), function (): void {
    it('can be created with pattern and schema', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()->type('string');
        $patternProperty = PatternProperty::create('^S_', $schema);

        expect($patternProperty->pattern())->toBe('^S_');
        expect($patternProperty->schema())->toBe($schema);
    });

    it('returns the schema via schema()', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()->type('integer');
        $patternProperty = PatternProperty::create('^I_', $schema);

        expect($patternProperty->schema())->toBe($schema);
    });

    it('schema serializes correctly', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()->type('boolean');
        $patternProperty = PatternProperty::create('^B_', $schema);

        $serialized = json_decode(json_encode($patternProperty->schema()), true);

        expect($serialized['type'])->toBe('boolean');
    });

    it('can hold complex regex patterns', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()->type('string');
        $patternProperty = PatternProperty::create('^[a-zA-Z][a-zA-Z0-9_]*$', $schema);

        expect($patternProperty->pattern())->toBe('^[a-zA-Z][a-zA-Z0-9_]*$');
    });

    it('can hold complex schemas', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->type('object')
            ->properties(
                Property::create(
                    'value',
                    LooseFluentDescriptor::withoutSchema()->type('number'),
                ),
            );

        $patternProperty = PatternProperty::create('^data_', $schema);

        $serialized = json_decode(json_encode($patternProperty->schema()), true);

        expect($serialized['type'])->toBe('object');
        expect($serialized['properties'])->toHaveKey('value');
    });
})->covers(PatternProperty::class);
