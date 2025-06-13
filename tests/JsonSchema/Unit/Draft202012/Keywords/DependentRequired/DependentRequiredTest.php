<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentRequired\Dependency;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentRequired\DependentRequired;

describe(class_basename(DependentRequired::class), function (): void {
    it('can create dependent required with no dependency', function (): void {
        $dependentRequired = DependentRequired::create();

        expect($dependentRequired->value())->toBe([]);
    });

    it('can create dependent required with a single dependency', function (): void {
        $dependency = Dependency::create('name', 'email', 'phone');
        $dependentRequired = DependentRequired::create($dependency);

        expect(\Safe\json_encode($dependentRequired))->toBe(
            \Safe\json_encode([
                'name' => ['email', 'phone'],
            ]),
        );
    });

    it('can create dependent required with multiple dependencies', function (): void {
        $nameDependency = Dependency::create('name', 'email', 'phone');
        $addressDependency = Dependency::create('address', 'city', 'country');

        $dependentRequired = DependentRequired::create($nameDependency, $addressDependency);

        expect(\Safe\json_encode($dependentRequired))->toBe(
            \Safe\json_encode([
                'name' => ['email', 'phone'],
                'address' => ['city', 'country'],
            ]),
        );
    });

    it('returns the correct name', function (): void {
        expect(DependentRequired::name())->toBe('dependentRequired');
    });
})->covers(DependentRequired::class);
