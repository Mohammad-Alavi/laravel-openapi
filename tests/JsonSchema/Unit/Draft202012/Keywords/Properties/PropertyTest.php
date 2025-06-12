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
})->covers(Property::class);
