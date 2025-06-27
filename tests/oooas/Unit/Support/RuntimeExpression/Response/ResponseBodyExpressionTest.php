<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Response\ResponseBodyExpression;

describe(class_basename(ResponseBodyExpression::class), function (): void {
    it('can be created with no JSON pointer', function (): void {
        $responseBodyExpression = ResponseBodyExpression::create();

        expect($responseBodyExpression->value())->toBe('$response.body')
            ->and($responseBodyExpression->jsonPointer())->toBe('');
    });

    it('can be created with a JSON pointer', function (): void {
        $responseBodyExpression = ResponseBodyExpression::create('/status');

        expect($responseBodyExpression->value())->toBe('$response.body#/status')
            ->and($responseBodyExpression->jsonPointer())->toBe('/status');
    });

    it('can be created with a full expression and no JSON pointer', function (): void {
        $responseBodyExpression = ResponseBodyExpression::create('$response.body');

        expect($responseBodyExpression->value())->toBe('$response.body')
            ->and($responseBodyExpression->jsonPointer())->toBe('');
    });

    it('can be created with a full expression and JSON pointer', function (): void {
        $responseBodyExpression = ResponseBodyExpression::create('$response.body#/data/id');

        expect($responseBodyExpression->value())->toBe('$response.body#/data/id')
            ->and($responseBodyExpression->jsonPointer())->toBe('/data/id');
    });

    it('throws an exception for an invalid JSON pointer', function (): void {
        expect(function (): ResponseBodyExpression {
            return ResponseBodyExpression::create('invalid');
        })->toThrow(
            InvalidArgumentException::class,
            'JSON pointer must start with "/", got "invalid"',
        );
    });

    it('throws an exception for a full expression with invalid format', function (): void {
        expect(function (): ResponseBodyExpression {
            return ResponseBodyExpression::create('$response.bodyinvalid');
        })->toThrow(
            InvalidArgumentException::class,
            'Body reference JSON pointer must start with "#", got "invalid"',
        );
    });

    it('can be serialized to JSON', function (): void {
        $responseBodyExpression = ResponseBodyExpression::create('/status');

        expect(json_encode($responseBodyExpression, JSON_UNESCAPED_SLASHES))->toBe('"$response.body#/status"');
    });
})->covers(ResponseBodyExpression::class);
