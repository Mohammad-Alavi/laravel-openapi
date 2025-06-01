<?php

use MohammadAlavi\LaravelOpenApi\Attributes\Extension;
use Tests\Doubles\Stubs\Attributes\ExtensionFactory;
use Tests\Doubles\Stubs\Attributes\ExtensionFactoryInvalid;

describe('Extension', function (): void {
    it('can handle null factory', function (): void {
        $extension = new Extension();
        expect($extension->factory)->toBeNull();
    });

    it('can handle null key', function (): void {
        $extension = new Extension();
        expect($extension->key)->toBeNull();
    });

    it('can handle null value', function (): void {
        $extension = new Extension();
        expect($extension->value)->toBeNull();
    });

    it('can set valid factory', function (): void {
        $extension = new Extension(factory: ExtensionFactory::class);
        expect($extension->factory)->toBe(ExtensionFactory::class);
    });

    it('can handle invalid factory', function (): void {
        expect(function (): void {
            new Extension(factory: ExtensionFactoryInvalid::class);
        })->toThrow(InvalidArgumentException::class);
    });

    it('can handle none existing factory', function (): void {
        expect(function (): void {
            new Extension(factory: 'NonExistentFactory');
        })->toThrow(InvalidArgumentException::class);
    });
})->covers(Extension::class);
