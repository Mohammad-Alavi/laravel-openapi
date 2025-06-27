<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\MethodExpression;

describe(class_basename(MethodExpression::class), function (): void {
    it('can be created with default value', function (): void {
        $methodExpression = MethodExpression::create();

        expect($methodExpression->value())->toBe('$method');
    });

    it('can be created with explicit value', function (): void {
        $methodExpression = MethodExpression::create('$method');

        expect($methodExpression->value())->toBe('$method');
    });

    it('throws an exception for an invalid value', function (): void {
        expect(function (): MethodExpression {
            return MethodExpression::create('invalid');
        })->toThrow(
            InvalidArgumentException::class,
            'Method expression must be "$method", got "invalid"',
        );
    });

    it('can be serialized to JSON', function (): void {
        $methodExpression = MethodExpression::create();

        expect(json_encode($methodExpression))->toBe('"$method"');
    });
})->covers(MethodExpression::class);
