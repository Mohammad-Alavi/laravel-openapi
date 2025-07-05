<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentRequired\Dependency;

describe(class_basename(Dependency::class), function (): void {
    it('can create a dependency with a property and no dependencies', function (): void {
        $dependency = Dependency::create('name');

        expect($dependency->property())->toBe('name')
            ->and($dependency->dependencies())->toBe([]);
    });

    it('can create a dependency with a property and a single dependency', function (): void {
        $dependency = Dependency::create('name', 'email');

        expect($dependency->property())->toBe('name')
            ->and($dependency->dependencies())->toBe(['email']);
    });

    it('can create a dependency with a property and multiple dependencies', function (): void {
        $dependency = Dependency::create('name', 'email', 'phone', 'address');

        expect($dependency->property())->toBe('name')
            ->and($dependency->dependencies())->toBe(['email', 'phone', 'address']);
    });

    it('returns the correct property', function (): void {
        $dependency = Dependency::create('test_property', 'dep1', 'dep2');

        expect($dependency->property())->toBe('test_property');
    });

    it('returns the correct dependencies', function (): void {
        $dependency = Dependency::create('property', 'dep1', 'dep2', 'dep3');

        expect($dependency->dependencies())->toBe(['dep1', 'dep2', 'dep3']);
    });
})->covers(Dependency::class);
