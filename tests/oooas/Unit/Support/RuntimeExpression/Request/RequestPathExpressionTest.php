<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Request\RequestPathExpression;

describe('RequestPathExpression', function (): void {
    it('can be created with a name', function (): void {
        $requestPathExpression = RequestPathExpression::create('id');

        expect($requestPathExpression->value())->toBe('$request.path.id');
        expect($requestPathExpression->name())->toBe('id');
    });

    it('can be created with a full expression', function (): void {
        $requestPathExpression = RequestPathExpression::create('$request.path.userId');

        expect($requestPathExpression->value())->toBe('$request.path.userId');
        expect($requestPathExpression->name())->toBe('userId');
    });

    it('throws an exception for an empty name', function (): void {
        expect(fn (): RequestPathExpression => RequestPathExpression::create(''))->toThrow(
            InvalidArgumentException::class,
            'Name cannot be empty',
        );
    });

    it('can be serialized to JSON', function (): void {
        $requestPathExpression = RequestPathExpression::create('id');

        expect(json_encode($requestPathExpression))->toBe('"$request.path.id"');
    });
})->covers(RequestPathExpression::class);
