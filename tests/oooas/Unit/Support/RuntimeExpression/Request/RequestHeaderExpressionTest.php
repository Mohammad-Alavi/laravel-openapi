<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Request\RequestHeaderExpression;

describe(class_basename(RequestHeaderExpression::class), function (): void {
    it('can be created with a token', function (): void {
        $requestHeaderExpression = RequestHeaderExpression::create('Content-Type');

        expect($requestHeaderExpression->value())->toBe('$request.header.Content-Type')
            ->and($requestHeaderExpression->token())->toBe('Content-Type');
    });

    it('can be created with a full expression', function (): void {
        $requestHeaderExpression = RequestHeaderExpression::create('$request.header.Accept');

        expect($requestHeaderExpression->value())->toBe('$request.header.Accept')
            ->and($requestHeaderExpression->token())->toBe('Accept');
    });

    it('throws an exception for an empty token', function (): void {
        expect(function (): RequestHeaderExpression {
            return RequestHeaderExpression::create('');
        })->toThrow(
            InvalidArgumentException::class,
            'Token cannot be empty',
        );
    });

    it('throws an exception for a token with invalid characters', function (): void {
        expect(function (): RequestHeaderExpression {
            return RequestHeaderExpression::create('Invalid@Header');
        })->toThrow(
            InvalidArgumentException::class,
            'Token contains invalid characters: "Invalid@Header"',
        );
    });

    it('can be serialized to JSON', function (): void {
        $requestHeaderExpression = RequestHeaderExpression::create('Content-Type');

        expect(json_encode($requestHeaderExpression))->toBe('"$request.header.Content-Type"');
    });
})->covers(RequestHeaderExpression::class);
