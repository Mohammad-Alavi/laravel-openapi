<?php

use MohammadAlavi\ObjectOrientedJSONSchema\FormatRegistry;
use MohammadAlavi\ObjectOrientedJSONSchema\Validation\Contracts\FormatValidator;

describe(class_basename(FormatRegistry::class), function (): void {
    it('can register a format without validator', function (): void {
        $registry = new FormatRegistry();

        $registry->register('phone-number');

        expect($registry->isKnown('phone-number'))->toBeTrue();
        expect($registry->hasValidator('phone-number'))->toBeFalse();
    });

    it('can register a format with validator', function (): void {
        $registry = new FormatRegistry();
        $validator = new class implements FormatValidator {
            public function validate(mixed $value): bool
            {
                return is_string($value) && str_contains($value, '@');
            }

            public function errorMessage(): string
            {
                return 'Must contain @';
            }
        };

        $registry->register('at-sign', $validator);

        expect($registry->isKnown('at-sign'))->toBeTrue();
        expect($registry->hasValidator('at-sign'))->toBeTrue();
        expect($registry->getValidator('at-sign'))->toBe($validator);
    });

    it('can register format with callable validator', function (): void {
        $registry = new FormatRegistry();

        $registry->registerWithCallable(
            'even-number',
            static fn ($v) => is_int($v) && 0 === $v % 2,
            'Must be an even number',
        );

        expect($registry->isKnown('even-number'))->toBeTrue();
        expect($registry->hasValidator('even-number'))->toBeTrue();
        expect($registry->validate('even-number', 4))->toBeTrue();
        expect($registry->validate('even-number', 3))->toBeFalse();
    });

    it('returns false for unknown format in isKnown', function (): void {
        $registry = new FormatRegistry();

        expect($registry->isKnown('unknown'))->toBeFalse();
    });

    it('returns null for unknown format validator', function (): void {
        $registry = new FormatRegistry();

        expect($registry->getValidator('unknown'))->toBeNull();
    });

    it('validate returns true for unknown formats', function (): void {
        $registry = new FormatRegistry();

        // Per JSON Schema spec, unknown formats should not cause validation failure
        expect($registry->validate('unknown-format', 'any value'))->toBeTrue();
    });

    it('validate returns true for annotation-only formats', function (): void {
        $registry = new FormatRegistry();
        $registry->register('phone-number'); // No validator

        expect($registry->validate('phone-number', 'any value'))->toBeTrue();
    });

    it('validate uses validator when present', function (): void {
        $registry = new FormatRegistry();
        $registry->registerWithCallable(
            'positive',
            static fn ($v) => is_int($v) && $v > 0,
        );

        expect($registry->validate('positive', 5))->toBeTrue();
        expect($registry->validate('positive', -1))->toBeFalse();
        expect($registry->validate('positive', 'string'))->toBeFalse();
    });

    it('can get all registered formats', function (): void {
        $registry = new FormatRegistry();
        $registry->register('format-a');
        $registry->register('format-b');
        $registry->registerWithCallable('format-c', static fn () => true);

        $formats = $registry->getFormats();

        expect($formats)->toContain('format-a');
        expect($formats)->toContain('format-b');
        expect($formats)->toContain('format-c');
        expect($formats)->toHaveCount(3);
    });

    it('can get validatable formats only', function (): void {
        $registry = new FormatRegistry();
        $registry->register('annotation-only');
        $registry->registerWithCallable('validatable', static fn () => true);

        expect($registry->getValidatableFormats())->toBe(['validatable']);
    });

    it('can get annotation-only formats', function (): void {
        $registry = new FormatRegistry();
        $registry->register('annotation-only');
        $registry->registerWithCallable('validatable', static fn () => true);

        expect($registry->getAnnotationOnlyFormats())->toBe(['annotation-only']);
    });

    it('can unregister a format', function (): void {
        $registry = new FormatRegistry();
        $registry->register('to-remove');

        expect($registry->isKnown('to-remove'))->toBeTrue();

        $registry->unregister('to-remove');

        expect($registry->isKnown('to-remove'))->toBeFalse();
    });

    it('can clear all formats', function (): void {
        $registry = new FormatRegistry();
        $registry->register('format-a');
        $registry->register('format-b');

        $registry->clear();

        expect($registry->getFormats())->toBeEmpty();
    });

    it('can create with standard formats', function (): void {
        $registry = FormatRegistry::withStandardFormats();

        expect($registry->isKnown('date-time'))->toBeTrue();
        expect($registry->isKnown('email'))->toBeTrue();
        expect($registry->isKnown('uri'))->toBeTrue();
        expect($registry->isKnown('uuid'))->toBeTrue();
        expect($registry->isKnown('ipv4'))->toBeTrue();
        expect($registry->isKnown('ipv6'))->toBeTrue();
        expect($registry->isKnown('hostname'))->toBeTrue();
        expect($registry->isKnown('json-pointer'))->toBeTrue();
        expect($registry->isKnown('regex'))->toBeTrue();
    });

    it('standard formats are annotation-only by default', function (): void {
        $registry = FormatRegistry::withStandardFormats();

        // Standard formats should not have validators by default
        expect($registry->hasValidator('email'))->toBeFalse();
        expect($registry->hasValidator('uri'))->toBeFalse();
    });

    it('supports fluent interface', function (): void {
        $registry = new FormatRegistry();

        $result = $registry
            ->register('format-a')
            ->register('format-b')
            ->registerWithCallable('format-c', static fn () => true)
            ->unregister('format-a');

        expect($result)->toBe($registry);
        expect($registry->isKnown('format-a'))->toBeFalse();
        expect($registry->isKnown('format-b'))->toBeTrue();
        expect($registry->isKnown('format-c'))->toBeTrue();
    });

    it('callable validator returns correct error message', function (): void {
        $registry = new FormatRegistry();
        $registry->registerWithCallable(
            'custom',
            static fn () => false,
            'Custom error message',
        );

        $validator = $registry->getValidator('custom');

        expect($validator->errorMessage())->toBe('Custom error message');
    });
})->covers(FormatRegistry::class);
