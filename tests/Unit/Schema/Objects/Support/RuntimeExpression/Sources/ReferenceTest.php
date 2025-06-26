<?php

declare(strict_types=1);

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Sources\BodyReference;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Sources\HeaderReference;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Sources\PathReference;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Sources\QueryReference;

describe('HeaderReference', function () {
    it('creates a valid reference', function (): void {
        $ref = HeaderReference::create('X-Test');

        expect($ref)->toBeInstanceOf(HeaderReference::class)
            ->and($ref->token())->toBe('X-Test')
            ->and($ref->toString())->toBe('header.X-Test');
    });

    it('throws on empty token', function (): void {
        expect(fn () => HeaderReference::create(''))->toThrow(InvalidArgumentException::class);
        expect(fn () => HeaderReference::create('0'))->toThrow(InvalidArgumentException::class);
    });

    it('throws on invalid characters', function (): void {
        expect(fn () => HeaderReference::create('bad token'))->toThrow(InvalidArgumentException::class);
    });
});

describe('QueryReference', function () {
    it('creates a valid reference', function (): void {
        $ref = QueryReference::create('param');

        expect($ref)->toBeInstanceOf(QueryReference::class)
            ->and($ref->name())->toBe('param')
            ->and($ref->toString())->toBe('query.param');
    });

    it('throws on empty name', function (): void {
        expect(fn () => QueryReference::create(''))->toThrow(InvalidArgumentException::class);
        expect(fn () => QueryReference::create('0'))->toThrow(InvalidArgumentException::class);
    });
});

describe('BodyReference', function () {
    it('creates a default reference', function (): void {
        $ref = BodyReference::create();

        expect($ref)->toBeInstanceOf(BodyReference::class)
            ->and($ref->jsonPointer())->toBe('')
            ->and($ref->toString())->toBe('body');
    });

    it('creates with valid json pointer', function (): void {
        $ref = BodyReference::create('/data');

        expect($ref->jsonPointer())->toBe('/data')
            ->and($ref->toString())->toBe('body#/data');
    });

    it('accepts pointer "0" as empty', function (): void {
        $ref = BodyReference::create('0');

        expect($ref->jsonPointer())->toBe('0')
            ->and($ref->toString())->toBe('body');
    });

    it('throws when pointer missing slash', function (): void {
        expect(fn () => BodyReference::create('data'))->toThrow(InvalidArgumentException::class);
    });

    it('throws on invalid escape sequence', function (): void {
        expect(fn () => BodyReference::create('/bad~x'))->toThrow(InvalidArgumentException::class);
    });
});

describe('PathReference', function () {
    it('creates a valid reference', function (): void {
        $ref = PathReference::create('id');

        expect($ref)->toBeInstanceOf(PathReference::class)
            ->and($ref->name())->toBe('id')
            ->and($ref->toString())->toBe('path.id');
    });

    it('throws on empty name', function (): void {
        expect(fn () => PathReference::create(''))->toThrow(InvalidArgumentException::class);
        expect(fn () => PathReference::create('0'))->toThrow(InvalidArgumentException::class);
    });
});
