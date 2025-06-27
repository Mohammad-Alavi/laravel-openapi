<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Sources\PathReference;

describe(class_basename(PathReference::class), function (): void {
    it('can be created with a valid name', function (): void {
        $pathReference = PathReference::create('id');

        expect($pathReference->name())->toBe('id')
            ->and($pathReference->toString())->toBe('path.id');
    });

    it('can be created with a name containing special characters', function (): void {
        $pathReference = PathReference::create('user-id');

        expect($pathReference->name())->toBe('user-id')
            ->and($pathReference->toString())->toBe('path.user-id');
    });

    it('throws an exception for an empty name', function (): void {
        expect(function (): PathReference {
            return PathReference::create('');
        })->toThrow(
            InvalidArgumentException::class,
            'Name cannot be empty',
        );
    });
})->covers(PathReference::class);
