<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\StatusCodeExpression;

describe('StatusCodeExpression', function (): void {
    it('can be created with default value', function (): void {
        $statusCodeExpression = StatusCodeExpression::create();

        expect($statusCodeExpression->value())->toBe('$statusCode');
    });

    it('can be created with explicit value', function (): void {
        $statusCodeExpression = StatusCodeExpression::create('$statusCode');

        expect($statusCodeExpression->value())->toBe('$statusCode');
    });

    it('throws an exception for an invalid value', function (): void {
        expect(fn (): StatusCodeExpression => StatusCodeExpression::create('invalid'))->toThrow(
            InvalidArgumentException::class,
            'StatusCode expression must be "$statusCode", got "invalid"',
        );
    });

    it('can be serialized to JSON', function (): void {
        $statusCodeExpression = StatusCodeExpression::create();

        expect(json_encode($statusCodeExpression))->toBe('"$statusCode"');
    });
})->covers(StatusCodeExpression::class);
