<?php

declare(strict_types=1);

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Deprecated;

describe(class_basename(Deprecated::class), function (): void {
    it('can be created with true and returns correct value', function (): void {
        $deprecated = Deprecated::create(true);
        expect($deprecated->value())->toBeTrue()
            ->and($deprecated->jsonSerialize())->toBeTrue();
    });

    it('can be created with false and returns correct value', function (): void {
        $deprecated = Deprecated::create(false);
        expect($deprecated->value())->toBeFalse()
            ->and($deprecated->jsonSerialize())->toBeFalse();
    });

    it('returns the correct name', function (): void {
        expect(Deprecated::name())->toBe('deprecated');
    });

    it('is immutable', function (): void {
        $deprecated = Deprecated::create(true);
        expect(fn () => $deprecated->value = false)->toThrow(Error::class);
    });
})->covers(Deprecated::class);
