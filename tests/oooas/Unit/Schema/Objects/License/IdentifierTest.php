<?php

namespace Tests\oooas\Unit\Schema\Objects\License;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\Fields\Identifier;

describe(class_basename(Identifier::class), function (): void {
    describe('factory methods', function (): void {
        it('can create MIT license', function (): void {
            $identifier = Identifier::mit();

            expect($identifier->value())->toBe('MIT')
                ->and($identifier->jsonSerialize())->toBe('MIT');
        });

        it('can create Apache 2.0 license', function (): void {
            $identifier = Identifier::apache2();

            expect($identifier->value())->toBe('Apache-2.0');
        });

        it('can create GPL 3.0 license', function (): void {
            $identifier = Identifier::gpl3();

            expect($identifier->value())->toBe('GPL-3.0');
        });

        it('can create BSD 3-Clause license', function (): void {
            $identifier = Identifier::bsd3();

            expect($identifier->value())->toBe('BSD-3-Clause');
        });

        it('can create ISC license', function (): void {
            $identifier = Identifier::isc();

            expect($identifier->value())->toBe('ISC');
        });

        it('can create MPL 2.0 license', function (): void {
            $identifier = Identifier::mpl2();

            expect($identifier->value())->toBe('MPL-2.0');
        });

        it('can create Unlicense', function (): void {
            $identifier = Identifier::unlicense();

            expect($identifier->value())->toBe('Unlicense');
        });
    });

    describe('custom identifiers', function (): void {
        it('can create custom SPDX identifier', function (): void {
            $identifier = Identifier::create('LGPL-2.1-or-later');

            expect($identifier->value())->toBe('LGPL-2.1-or-later');
        });

        it('accepts valid SPDX characters', function (): void {
            expect(fn () => Identifier::create('GPL-3.0+'))->not->toThrow(\InvalidArgumentException::class)
                ->and(fn () => Identifier::create('Apache-2.0'))->not->toThrow(\InvalidArgumentException::class)
                ->and(fn () => Identifier::create('BSD.3.Clause'))->not->toThrow(\InvalidArgumentException::class);
        });
    });

    describe('validation', function (): void {
        it('rejects empty identifier', function (): void {
            expect(fn () => Identifier::create(''))->toThrow(\InvalidArgumentException::class);
        });

        it('rejects invalid characters', function (): void {
            expect(fn () => Identifier::create('MIT License'))->toThrow(\InvalidArgumentException::class)
                ->and(fn () => Identifier::create('GPL/3.0'))->toThrow(\InvalidArgumentException::class)
                ->and(fn () => Identifier::create('MIT@2.0'))->toThrow(\InvalidArgumentException::class);
        });
    });
})->covers(Identifier::class);
