<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;

describe(class_basename(Name::class), function (): void {
    it('throws if empty string provided', function (): void
    {
        expect(function (): void {
            Name::create('');
        })->toThrow(\InvalidArgumentException::class);
    });
})->covers(Name::class);