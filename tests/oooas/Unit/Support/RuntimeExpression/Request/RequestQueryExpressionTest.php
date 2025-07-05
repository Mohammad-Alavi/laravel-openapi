<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\Request\RequestQueryExpression;

describe(class_basename(RequestQueryExpression::class), function (): void {
    it('can be created with a name', function (): void {
        $requestQueryExpression = RequestQueryExpression::create('filter');

        expect($requestQueryExpression->value())->toBe('$request.query.filter')
            ->and($requestQueryExpression->name())->toBe('filter');
    });

    it('can be created with a full expression', function (): void {
        $requestQueryExpression = RequestQueryExpression::create('$request.query.sort');

        expect($requestQueryExpression->value())->toBe('$request.query.sort')
            ->and($requestQueryExpression->name())->toBe('sort');
    });

    it('throws an exception for an empty name', function (): void {
        expect(function (): RequestQueryExpression {
            return RequestQueryExpression::create('');
        })->toThrow(
            InvalidArgumentException::class,
            'Name cannot be empty',
        );
    });

    it('can be serialized to JSON', function (): void {
        $requestQueryExpression = RequestQueryExpression::create('filter');

        expect(json_encode($requestQueryExpression))->toBe('"$request.query.filter"');
    });
})->covers(RequestQueryExpression::class);
