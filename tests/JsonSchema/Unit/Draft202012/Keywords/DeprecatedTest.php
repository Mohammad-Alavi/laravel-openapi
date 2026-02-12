<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Deprecated;

describe(class_basename(Deprecated::class), function (): void {
    it('can be created with true and returns correct value', function (): void {
        $deprecated = Deprecated::create();
        expect($deprecated->value())->toBeTrue()
            ->and($deprecated->jsonSerialize())->toBeTrue();
    });

    it('returns the correct name', function (): void {
        expect(Deprecated::name())->toBe('deprecated');
    });

    it('is immutable', function (): void {
        expect(Deprecated::class)->toBeImmutable();
    });
})->covers(Deprecated::class);
