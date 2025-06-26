<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Request\RequestQueryExpression;

describe('RequestQueryExpression', function (): void {
    it('can be created with a name', function (): void {
        $requestQueryExpression = RequestQueryExpression::create('filter');

        expect($requestQueryExpression->value())->toBe('$request.query.filter');
        expect($requestQueryExpression->name())->toBe('filter');
    });

    it('can be created with a full expression', function (): void {
        $requestQueryExpression = RequestQueryExpression::create('$request.query.sort');

        expect($requestQueryExpression->value())->toBe('$request.query.sort');
        expect($requestQueryExpression->name())->toBe('sort');
    });

    it('throws an exception for an empty name', function (): void {
        expect(fn (): RequestQueryExpression => RequestQueryExpression::create(''))->toThrow(
            InvalidArgumentException::class,
            'Name cannot be empty',
        );
    });

    it('can be serialized to JSON', function (): void {
        $requestQueryExpression = RequestQueryExpression::create('filter');

        expect(json_encode($requestQueryExpression))->toBe('"$request.query.filter"');
    });
})->covers(RequestQueryExpression::class);
