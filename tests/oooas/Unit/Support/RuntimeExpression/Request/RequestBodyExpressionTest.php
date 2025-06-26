<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Request\RequestBodyExpression;

describe('RequestBodyExpression', function (): void {
    it('can be created with no JSON pointer', function (): void {
        $requestBodyExpression = RequestBodyExpression::create();

        expect($requestBodyExpression->value())->toBe('$request.body');
        expect($requestBodyExpression->jsonPointer())->toBe('');
    });

    it('can be created with a JSON pointer', function (): void {
        $requestBodyExpression = RequestBodyExpression::create('/user/id');

        expect($requestBodyExpression->value())->toBe('$request.body#/user/id');
        expect($requestBodyExpression->jsonPointer())->toBe('/user/id');
    });

    it('can be created with a full expression and no JSON pointer', function (): void {
        $requestBodyExpression = RequestBodyExpression::create('$request.body');

        expect($requestBodyExpression->value())->toBe('$request.body');
        expect($requestBodyExpression->jsonPointer())->toBe('');
    });

    it('can be created with a full expression and JSON pointer', function (): void {
        $requestBodyExpression = RequestBodyExpression::create('$request.body#/user/id');

        expect($requestBodyExpression->value())->toBe('$request.body#/user/id');
        expect($requestBodyExpression->jsonPointer())->toBe('/user/id');
    });

    it('throws an exception for an invalid JSON pointer', function (): void {
        expect(fn (): RequestBodyExpression => RequestBodyExpression::create('invalid'))->toThrow(
            InvalidArgumentException::class,
            'JSON pointer must start with "/", got "invalid"',
        );
    });

    it('throws an exception for a full expression with invalid format', function (): void {
        expect(fn (): RequestBodyExpression => RequestBodyExpression::create('$request.bodyinvalid'))->toThrow(
            InvalidArgumentException::class,
            'Body reference JSON pointer must start with "#", got "invalid"',
        );
    });

    it('can be serialized to JSON', function (): void {
        $requestBodyExpression = RequestBodyExpression::create('/user/id');

        expect(json_encode($requestBodyExpression, JSON_UNESCAPED_SLASHES))->toBe('"$request.body#/user/id"');
    });
})->covers(RequestBodyExpression::class);
