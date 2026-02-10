<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Properties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(Properties::class), function (): void {
    it('can create properties with no property', function (): void {
        $properties = Properties::create();

        expect($properties->value())->toBe([]);
    });

    it('can create properties with a single property', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()->type(Type::string());
        $property = Property::create('name', $descriptor);
        $properties = Properties::create($property);

        expect(\Safe\json_encode($properties))->toBe(
            \Safe\json_encode([
                'name' => $descriptor,
            ]),
        );
    });

    it('can create properties with multiple properties', function (): void {
        $nameDescriptor = LooseFluentDescriptor::withoutSchema()->type(Type::string());
        $ageDescriptor = LooseFluentDescriptor::withoutSchema()->type(Type::integer());

        $nameProperty = Property::create('name', $nameDescriptor);
        $ageProperty = Property::create('age', $ageDescriptor);

        $properties = Properties::create($nameProperty, $ageProperty);

        expect(\Safe\json_encode($properties))->toBe(
            \Safe\json_encode([
                'name' => $nameDescriptor,
                'age' => $ageDescriptor,
            ]),
        );
    });

    it('returns the correct name', function (): void {
        expect(Properties::name())->toBe('properties');
    });
})->covers(Properties::class);
