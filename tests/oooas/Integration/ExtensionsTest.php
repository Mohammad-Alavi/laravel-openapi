<?php

namespace Tests\oooas\Unit\Utilities;

use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Review\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extension;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response;
use Webmozart\Assert\InvalidArgumentException;

describe('Extensions', function (): void {
    $expectations = [
        'x-key' => 'value',
        'x-foo' => 'bar',
        'x-baz' => null,
        'x-obj' => [],
    ];
    dataset('extensibleObjectSet', [
        [
            fn (): Components => Components::create(), $expectations],
        [
            fn (): Operation => Operation::create(), $expectations + [
                'responses' => [
                    'default' => [
                        'description' => 'Default Response',
                    ],
                ],
            ],
        ],
        [
            fn (): PathItem => PathItem::create(), $expectations],
        [
            fn (): Response => Response::ok(), $expectations  + [
                'description' => 'OK',
            ],
        ],
        [
            fn (): Descriptor => Schema::object(), $expectations  + [
                'type' => 'object',
            ],
        ],
    ]);

    it('can create objects with extension', function (ExtensibleObject $extensibleObject, array $expectations): void {
        $object = new \stdClass();
        $extension1 = Extension::create('x-key', 'value');
        $extension2 = Extension::create('x-foo', 'bar');
        $extension3 = Extension::create('x-baz', null);
        $extension4 = Extension::create('x-obj', $object);
        $sut = $extensibleObject
            ->addExtension($extension1)
            ->addExtension($extension2)
            ->addExtension($extension3)
            ->addExtension($extension4);

        expect($sut->asArray())->toEqual($expectations);
    })->with('extensibleObjectSet');

    it('can unset extensions', function (): void {
        $object = Schema::object()
            ->addExtension(Extension::create('x-key', 'value'))
            ->addExtension(Extension::create('x-foo', 'bar'))
            ->addExtension(Extension::create('x-baz', null));

        $object = $object->removeExtension('x-key');

        expect($object->asArray())->toEqualCanonicalizing([
            'x-foo' => 'bar',
            'x-baz' => null,
            'type' => 'object',
        ]);
    });

    it('gets single extension', function (ExtensibleObject $extensibleObject): void {
        $extension = Extension::create('x-foo', 'bar');
        $object = $extensibleObject->addExtension($extension);

        expect($object)->getExtension('x-foo')->equals($extension)->toBeTrue();
    })->with('extensibleObjectSet');

    it('throws exception when extension dont exist', function (ExtensibleObject $extensibleObject): void {
        expect(function () use ($extensibleObject): void {
            $extensibleObject->getExtension('x-key');
        })->toThrow(InvalidArgumentException::class);
    })->with('extensibleObjectSet');

    it('gets all extensions', function (ExtensibleObject $extensibleObject): void {
        expect($extensibleObject->extensions())->toBeArray()
        ->each(function ($extension): void {
            expect($extension)->toBeInstanceOf(Extension::class);
        });

        $extension1 = Extension::create('x-key', 'value');
        $extension2 = Extension::create('x-foo', 'bar');
        $object = $extensibleObject
            ->addExtension($extension1)
            ->addExtension($extension2);

        expect($object->extensions())->toBe([$extension1, $extension2]);
    })->with('extensibleObjectSet');

    it('throws exception when extension does not exist', function (ExtensibleObject $extensibleObject): void {
        expect(function () use ($extensibleObject): void {
            $extensibleObject->getExtension('x-key');
        })->toThrow(InvalidArgumentException::class, 'Extension not found: x-key');
    })->with('extensibleObjectSet');
})->coversNothing();
