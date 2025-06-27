<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Sources\HeaderReference;

describe(class_basename(HeaderReference::class), function (): void {
    it('can be created with a valid token', function (): void {
        $headerReference = HeaderReference::create('Content-Type');

        expect($headerReference->token())->toBe('Content-Type')
            ->and($headerReference->toString())->toBe('header.Content-Type');
    });

    it('can be created with a token containing special characters', function (): void {
        $headerReference = HeaderReference::create('X-Custom-Header!#$%&\'*+-.^_`|~');

        expect($headerReference->token())->toBe('X-Custom-Header!#$%&\'*+-.^_`|~')
            ->and($headerReference->toString())->toBe('header.X-Custom-Header!#$%&\'*+-.^_`|~');
    });

    it('throws an exception for an empty token', function (): void {
        expect(function (): HeaderReference {
            return HeaderReference::create('');
        })->toThrow(
            InvalidArgumentException::class,
            'Token cannot be empty',
        );
    });

    it('throws an exception for a token with invalid characters', function (): void {
        expect(function (): HeaderReference {
            return HeaderReference::create('Invalid@Header');
        })->toThrow(
            InvalidArgumentException::class,
            'Token contains invalid characters: "Invalid@Header"',
        );
    });
})->covers(HeaderReference::class);
