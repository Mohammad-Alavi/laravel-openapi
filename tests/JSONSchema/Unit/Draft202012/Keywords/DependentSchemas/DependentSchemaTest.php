<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentSchemas\DependentSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(DependentSchema::class), function (): void {
    it('can be created with property and schema', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()->required('billing_address');
        $dependentSchema = DependentSchema::create('credit_card', $schema);

        expect($dependentSchema->property())->toBe('credit_card');
        expect($dependentSchema->schema())->toBe($schema);
    });

    it('returns the schema via schema()', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()->required('email');
        $dependentSchema = DependentSchema::create('name', $schema);

        expect($dependentSchema->schema())->toBe($schema);
    });

    it('schema serializes correctly', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()->required('phone');
        $dependentSchema = DependentSchema::create('contact', $schema);

        $serialized = json_decode(json_encode($dependentSchema->schema()), true);

        expect($serialized['required'])->toBe(['phone']);
    });

    it('can hold complex schemas', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->properties(
                Property::create('billing_address', LooseFluentDescriptor::withoutSchema()->type('string')),
                Property::create('cvv', LooseFluentDescriptor::withoutSchema()->type('string')->pattern('^\d{3,4}$')),
            )
            ->required('billing_address', 'cvv');

        $dependentSchema = DependentSchema::create('credit_card', $schema);

        $serialized = json_decode(json_encode($dependentSchema->schema()), true);

        expect($serialized['properties'])->toHaveKeys(['billing_address', 'cvv']);
        expect($serialized['required'])->toBe(['billing_address', 'cvv']);
    });
})->covers(DependentSchema::class);
