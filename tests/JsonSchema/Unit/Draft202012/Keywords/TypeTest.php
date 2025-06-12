<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;

describe(class_basename(Type::class), function (): void {
    it('can create a type with a string value', function (): void {
        $type = Type::create('string');

        expect($type->value())->toBe('string');
    });

    it('can create a type with multiple values', function (): void {
        $type = Type::create('string', 'number');

        expect($type->value())->toBe(['string', 'number']);
    });

    it(
        'can create a type with static methods',
        function (string $method, string $expectedValue): void {
            $type = Type::{$method}();

            expect($type->value())->toBe($expectedValue);
        },
    )->with([
        ['null', 'null'],
        ['boolean', 'boolean'],
        ['string', 'string'],
        ['integer', 'integer'],
        ['number', 'number'],
        ['object', 'object'],
        ['array', 'array'],
    ]);

    it('returns the name of the keyword', function (): void {
        expect(Type::name())->toBe('type');
    });

    it('throws an exception for invalid type', function (): void {
        expect(fn (): Type => Type::create('invalid'))
            ->toThrow(InvalidArgumentException::class);
    });

    it('throws an exception for duplicate types', function (): void {
        expect(fn (): Type => Type::create('string', 'string'))
            ->toThrow(InvalidArgumentException::class);
    });

    it('returns a single value instead of an array for a single type', function (): void {
        $type = Type::create('string');

        expect($type->value())->toBe('string')
            ->and($type->value())->not->toBeArray();
    });
})->covers(Type::class);
