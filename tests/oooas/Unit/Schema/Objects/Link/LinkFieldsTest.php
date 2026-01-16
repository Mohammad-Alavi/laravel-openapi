<?php

namespace Tests\oooas\Unit\Schema\Objects\Link;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Fields\OperationId;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Fields\OperationRef;

describe(class_basename(OperationRef::class), function (): void {
    it('can create with valid URI reference', function (): void {
        $ref = OperationRef::create('#/paths/~1users/get');

        expect($ref->value())->toBe('#/paths/~1users/get')
            ->and($ref->jsonSerialize())->toBe('#/paths/~1users/get');
    });

    it('accepts absolute URI', function (): void {
        $ref = OperationRef::create('https://example.com/openapi.json#/paths/~1users/get');

        expect($ref->value())->toBe('https://example.com/openapi.json#/paths/~1users/get');
    });

    it('accepts relative URI', function (): void {
        $ref = OperationRef::create('./other.json#/paths/~1items/post');

        expect($ref->value())->toBe('./other.json#/paths/~1items/post');
    });

    it('rejects empty reference', function (): void {
        expect(fn () => OperationRef::create(''))->toThrow(\InvalidArgumentException::class);
    });
})->covers(OperationRef::class);

describe(class_basename(OperationId::class), function (): void {
    it('can create with valid operation ID', function (): void {
        $id = OperationId::create('getUsers');

        expect($id->value())->toBe('getUsers')
            ->and($id->jsonSerialize())->toBe('getUsers');
    });

    it('accepts dots, underscores, and hyphens', function (): void {
        expect(fn () => OperationId::create('users.get'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => OperationId::create('users_get'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => OperationId::create('users-get'))->not->toThrow(\InvalidArgumentException::class)
            ->and(fn () => OperationId::create('users.get_all-items'))->not->toThrow(\InvalidArgumentException::class);
    });

    it('rejects empty operation ID', function (): void {
        expect(fn () => OperationId::create(''))->toThrow(\InvalidArgumentException::class);
    });

    it('rejects invalid characters', function (): void {
        expect(fn () => OperationId::create('get users'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => OperationId::create('get/users'))->toThrow(\InvalidArgumentException::class)
            ->and(fn () => OperationId::create('get@users'))->toThrow(\InvalidArgumentException::class);
    });
})->covers(OperationId::class);
