<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\URLExpression;

describe(class_basename(URLExpression::class), function (): void {
    it('can be created with default value', function (): void {
        $urlExpression = URLExpression::create();

        expect($urlExpression->value())->toBe('$url');
    });

    it('can be created with explicit value', function (): void {
        $urlExpression = URLExpression::create('$url');

        expect($urlExpression->value())->toBe('$url');
    });

    it('throws an exception for an invalid value', function (): void {
        expect(function (): URLExpression {
            return URLExpression::create('invalid');
        })->toThrow(
            InvalidArgumentException::class,
            'URL expression must be "$url", got "invalid"',
        );
    });

    it('can be serialized to JSON', function (): void {
        $urlExpression = URLExpression::create();

        expect(json_encode($urlExpression))->toBe('"$url"');
    });
})->covers(URLExpression::class);
