<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;

describe(class_basename(Property::class), function (): void {
    it('can create a property with a name and descriptor', function (): void {
        $descriptor = Descriptor::withoutSchema()->type('string');
        $property = Property::create('name', $descriptor);

        expect($property->name())->toBe('name')
            ->and($property->schema())->toBe($descriptor);
    });

    it('returns the correct name', function (): void {
        $descriptor = Descriptor::withoutSchema()->type('string');
        $property = Property::create('test_property', $descriptor);

        expect($property->name())->toBe('test_property');
    });

    it('returns the correct schema', function (): void {
        $descriptor = Descriptor::withoutSchema()
            ->type('string')
            ->minLength(5)
            ->maxLength(10);
        $property = Property::create('name', $descriptor);

        expect($property->schema())->toBe($descriptor)
            ->and($property->schema()->jsonSerialize())->toBe([
                'type' => 'string',
                'maxLength' => 10,
                'minLength' => 5,
            ]);
    });
})->covers(Property::class);
