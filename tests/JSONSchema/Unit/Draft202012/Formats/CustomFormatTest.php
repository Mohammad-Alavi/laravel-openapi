<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\CustomFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;

describe(class_basename(CustomFormat::class), function (): void {
    it('implements DefinedFormat interface', function (): void {
        $format = CustomFormat::create('phone-number');

        expect($format)->toBeInstanceOf(DefinedFormat::class);
    });

    it('can create with any string', function (): void {
        $format = CustomFormat::create('my-custom-format');

        expect($format->value())->toBe('my-custom-format');
    });

    it('can create with special characters', function (): void {
        $format = CustomFormat::create('x-my-app/special_format.v2');

        expect($format->value())->toBe('x-my-app/special_format.v2');
    });

    it('can create with empty string', function (): void {
        $format = CustomFormat::create('');

        expect($format->value())->toBe('');
    });

    it('preserves exact string value', function (): void {
        $cases = [
            'phone-number',
            'credit-card',
            'social-security-number',
            'postal-code',
            'x-custom',
        ];

        foreach ($cases as $value) {
            expect(CustomFormat::create($value)->value())->toBe($value);
        }
    });
})->covers(CustomFormat::class);
