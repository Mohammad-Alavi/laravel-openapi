<?php

namespace Tests\oooas\Unit\Schema\Objects\License;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\License;

describe(class_basename(License::class), function (): void {
    describe('basic creation', function (): void {
        it('can create with name', function (): void {
            $license = License::create('My License');

            expect($license->compile())->toBe([
                'name' => 'My License',
            ]);
        });

        it('can add identifier', function (): void {
            $license = License::create('MIT License')
                ->identifier('MIT');

            expect($license->compile())->toBe([
                'name' => 'MIT License',
                'identifier' => 'MIT',
            ]);
        });

        it('can add url', function (): void {
            $license = License::create('Custom License')
                ->url('https://example.com/license');

            expect($license->compile())->toBe([
                'name' => 'Custom License',
                'url' => 'https://example.com/license',
            ]);
        });

        it('rejects both identifier and url', function (): void {
            $license = License::create('Test')->identifier('MIT');

            expect(fn () => $license->url('https://example.com'))->toThrow(\InvalidArgumentException::class);
        });
    });

    describe('convenience methods', function (): void {
        it('can create MIT license', function (): void {
            $license = License::mit();

            $compiled = $license->compile();

            expect($compiled['name'])->toBe('MIT License')
                ->and($compiled['identifier'])->toBe('MIT');
        });

        it('can create Apache 2.0 license', function (): void {
            $license = License::apache2();

            $compiled = $license->compile();

            expect($compiled['name'])->toBe('Apache License 2.0')
                ->and($compiled['identifier'])->toBe('Apache-2.0');
        });

        it('can create GPL 3.0 license', function (): void {
            $license = License::gpl3();

            $compiled = $license->compile();

            expect($compiled['name'])->toBe('GNU General Public License v3.0')
                ->and($compiled['identifier'])->toBe('GPL-3.0');
        });

        it('can create BSD 3-Clause license', function (): void {
            $license = License::bsd3();

            $compiled = $license->compile();

            expect($compiled['name'])->toBe('BSD 3-Clause License')
                ->and($compiled['identifier'])->toBe('BSD-3-Clause');
        });

        it('can create ISC license', function (): void {
            $license = License::isc();

            $compiled = $license->compile();

            expect($compiled['name'])->toBe('ISC License')
                ->and($compiled['identifier'])->toBe('ISC');
        });

        it('can create MPL 2.0 license', function (): void {
            $license = License::mpl2();

            $compiled = $license->compile();

            expect($compiled['name'])->toBe('Mozilla Public License 2.0')
                ->and($compiled['identifier'])->toBe('MPL-2.0');
        });

        it('can create Unlicense', function (): void {
            $license = License::unlicense();

            $compiled = $license->compile();

            expect($compiled['name'])->toBe('The Unlicense')
                ->and($compiled['identifier'])->toBe('Unlicense');
        });

        it('can create proprietary license', function (): void {
            $license = License::proprietary('https://example.com/license');

            $compiled = $license->compile();

            expect($compiled['name'])->toBe('Proprietary')
                ->and($compiled['url'])->toBe('https://example.com/license');
        });
    });
})->covers(License::class);
