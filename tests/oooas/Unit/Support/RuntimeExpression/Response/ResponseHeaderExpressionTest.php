<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Response\ResponseHeaderExpression;

describe('ResponseHeaderExpression', function (): void {
    it('can be created with a token', function (): void {
        $responseHeaderExpression = ResponseHeaderExpression::create('Content-Type');

        expect($responseHeaderExpression->value())->toBe('$response.header.Content-Type');
        expect($responseHeaderExpression->token())->toBe('Content-Type');
    });

    it('can be created with a full expression', function (): void {
        $responseHeaderExpression = ResponseHeaderExpression::create('$response.header.Server');

        expect($responseHeaderExpression->value())->toBe('$response.header.Server');
        expect($responseHeaderExpression->token())->toBe('Server');
    });

    it('throws an exception for an empty token', function (): void {
        expect(fn (): ResponseHeaderExpression => ResponseHeaderExpression::create(''))->toThrow(
            InvalidArgumentException::class,
            'Token cannot be empty',
        );
    });

    it('throws an exception for a token with invalid characters', function (): void {
        expect(fn (): ResponseHeaderExpression => ResponseHeaderExpression::create('Invalid@Header'))->toThrow(
            InvalidArgumentException::class,
            'Token contains invalid characters: "Invalid@Header"',
        );
    });

    it('can be serialized to JSON', function (): void {
        $responseHeaderExpression = ResponseHeaderExpression::create('Content-Type');

        expect(json_encode($responseHeaderExpression))->toBe('"$response.header.Content-Type"');
    });
})->covers(ResponseHeaderExpression::class);
