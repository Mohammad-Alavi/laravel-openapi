<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Properties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;

describe(class_basename(Properties::class), function (): void {
    it('can create properties with no property', function (): void {
        $properties = Properties::create();

        expect($properties->value())->toBe([]);
    });

    it('can create properties with a single property', function (): void {
        $descriptor = Descriptor::withoutSchema()->type('string');
        $property = Property::create('name', $descriptor);
        $properties = Properties::create($property);

        expect(json_encode($properties))->toBe(
            json_encode([
                'name' => $descriptor,
            ]),
        );
    });

    it('can create properties with multiple properties', function (): void {
        $nameDescriptor = Descriptor::withoutSchema()->type('string');
        $ageDescriptor = Descriptor::withoutSchema()->type('integer');

        $nameProperty = Property::create('name', $nameDescriptor);
        $ageProperty = Property::create('age', $ageDescriptor);

        $properties = Properties::create($nameProperty, $ageProperty);

        expect(json_encode($properties))->toBe(
            json_encode([
                'name' => $nameDescriptor,
                'age' => $ageDescriptor,
            ]),
        );
    });

    it('returns the correct name', function (): void {
        expect(Properties::name())->toBe('properties');
    });
})->covers(Properties::class);
