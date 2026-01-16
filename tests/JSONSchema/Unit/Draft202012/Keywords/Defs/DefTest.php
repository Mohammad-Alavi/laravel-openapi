<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs\Def;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(Def::class), function (): void {
    it('can be created with name and schema', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()->type('string');
        $def = Def::create('address', $schema);

        expect($def->name())->toBe('address');
        expect($def->schema())->toBe($schema);
    });

    it('returns the schema', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()->type('object');
        $def = Def::create('person', $schema);

        expect($def->schema())->toBe($schema);
    });

    it('schema serializes correctly', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()->type('integer')->minimum(0);
        $def = Def::create('positiveInt', $schema);

        $serialized = json_decode(json_encode($def->schema()), true);

        expect($serialized['type'])->toBe('integer');
        expect($serialized['minimum'])->toBe(0);
    });

    it('can hold complex schemas', function (): void {
        $schema = LooseFluentDescriptor::withoutSchema()
            ->type('object')
            ->properties(
                MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property::create(
                    'street',
                    LooseFluentDescriptor::withoutSchema()->type('string'),
                ),
                MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property::create(
                    'city',
                    LooseFluentDescriptor::withoutSchema()->type('string'),
                ),
            )
            ->required('street', 'city');

        $def = Def::create('address', $schema);

        $serialized = json_decode(json_encode($def->schema()), true);

        expect($serialized['type'])->toBe('object');
        expect($serialized['properties'])->toHaveKeys(['street', 'city']);
        expect($serialized['required'])->toBe(['street', 'city']);
    });
})->covers(Def::class);
