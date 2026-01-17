<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extension;
use Tests\oooas\Support\Doubles\Fakes\ExtensibleObjectFake;
use Webmozart\Assert\InvalidArgumentException;

describe(class_basename(ExtensibleObject::class), function (): void {
    describe('addExtension', function (): void {
        it('can add a single extension', function (): void {
            $object = ExtensibleObjectFake::create();
            $extension = Extension::create('x-test', 'value');

            $result = $object->addExtension($extension);

            expect($result->extensions())->toHaveCount(1)
                ->and($result->extensions()[0])->equals($extension)->toBeTrue();
        });

        it('can add multiple extensions at once', function (): void {
            $object = ExtensibleObjectFake::create();
            $extension1 = Extension::create('x-test', 'value');
            $extension2 = Extension::create('x-foo', 'bar');

            $result = $object->addExtension($extension1, $extension2);

            expect($result->extensions())->toHaveCount(2)
                ->and($result->getExtension('x-test'))->equals($extension1)->toBeTrue()
                ->and($result->getExtension('x-foo'))->equals($extension2)->toBeTrue();
        });

        it('can chain addExtension calls', function (): void {
            $object = ExtensibleObjectFake::create();
            $extension1 = Extension::create('x-first', 'value1');
            $extension2 = Extension::create('x-second', 'value2');

            $result = $object
                ->addExtension($extension1)
                ->addExtension($extension2);

            expect($result->extensions())->toHaveCount(2)
                ->and($result->getExtension('x-first'))->equals($extension1)->toBeTrue()
                ->and($result->getExtension('x-second'))->equals($extension2)->toBeTrue();
        });

        it('overwrites extension with same name', function (): void {
            $object = ExtensibleObjectFake::create();
            $original = Extension::create('x-test', 'original');
            $replacement = Extension::create('x-test', 'replaced');

            $result = $object
                ->addExtension($original)
                ->addExtension($replacement);

            expect($result->extensions())->toHaveCount(1)
                ->and($result->getExtension('x-test')->value())->toBe('replaced');
        });

        it('does not mutate the original object when adding extension', function (): void {
            $original = ExtensibleObjectFake::create();
            $extension = Extension::create('x-test', 'value');

            $modified = $original->addExtension($extension);

            expect($original->extensions())->toBeEmpty()
                ->and($modified->extensions())->toHaveCount(1);
        });

        it('does not mutate the original object when chaining additions', function (): void {
            $extension1 = Extension::create('x-first', 'value1');
            $extension2 = Extension::create('x-second', 'value2');
            $original = ExtensibleObjectFake::create()->addExtension($extension1);

            $modified = $original->addExtension($extension2);

            expect($original->extensions())->toHaveCount(1)
                ->and($original->getExtension('x-first'))->equals($extension1)->toBeTrue()
                ->and($modified->extensions())->toHaveCount(2);
        });
    });

    describe('removeExtension', function (): void {
        it('can remove an extension', function (): void {
            $extension = Extension::create('x-test', 'value');
            $object = ExtensibleObjectFake::create()->addExtension($extension);

            $result = $object->removeExtension('x-test');

            expect($result->extensions())->toBeEmpty();
        });

        it('throws exception when removing non-existent extension', function (): void {
            $object = ExtensibleObjectFake::create();

            expect(fn () => $object->removeExtension('x-nonexistent'))
                ->toThrow(InvalidArgumentException::class, 'Extension not found: x-nonexistent');
        });

        it('does not mutate the original object when removing extension', function (): void {
            $extension = Extension::create('x-test', 'value');
            $original = ExtensibleObjectFake::create()->addExtension($extension);

            $modified = $original->removeExtension('x-test');

            expect($original->extensions())->toHaveCount(1)
                ->and($modified->extensions())->toBeEmpty();
        });
    });

    describe('getExtension', function (): void {
        it('can get an extension by name', function (): void {
            $extension = Extension::create('x-test', 'value');
            $object = ExtensibleObjectFake::create()->addExtension($extension);

            $result = $object->getExtension('x-test');

            expect($result)->equals($extension)->toBeTrue();
        });

        it('throws exception when getting non-existent extension', function (): void {
            $object = ExtensibleObjectFake::create();

            expect(fn () => $object->getExtension('x-nonexistent'))
                ->toThrow(InvalidArgumentException::class, 'Extension not found: x-nonexistent');
        });
    });

    describe('extensions', function (): void {
        it('returns empty array when no extensions', function (): void {
            $object = ExtensibleObjectFake::create();

            expect($object->extensions())->toBeEmpty();
        });

        it('returns all extensions', function (): void {
            $extension1 = Extension::create('x-first', 'value1');
            $extension2 = Extension::create('x-second', 'value2');
            $object = ExtensibleObjectFake::create()->addExtension($extension1, $extension2);

            $result = $object->extensions();

            expect($result)->toHaveCount(2)
                ->and($result)->toBe([$extension1, $extension2]);
        });
    });

    describe('serialization', function (): void {
        it('serializes extensions in output', function (): void {
            $object = ExtensibleObjectFake::create();
            $extension1 = Extension::create('x-test', 'value');
            $extension2 = Extension::create('x-foo', 'bar');
            $object = $object->addExtension($extension1, $extension2);

            $result = $object->compile();

            expect($result)->toBe([
                'x-test' => 'value',
                'x-foo' => 'bar',
            ]);
        });

        it('serializes empty when no extensions', function (): void {
            $object = ExtensibleObjectFake::create();

            $result = $object->compile();

            expect($result)->toBe([]);
        });
    });
})->covers(ExtensibleObject::class);
