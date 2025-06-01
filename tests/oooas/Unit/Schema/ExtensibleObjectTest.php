<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Exceptions\PropertyDoesNotExistException;
use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extension;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use Tests\oooas\Doubles\Fakes\ExtensibleObjectFake;

describe('ExtensibleObject', function (): void {
    it('can manipulate extensions', function (): void {
        $object = ExtensibleObjectFake::create();
        $extension1 = Extension::create('x-test', 'value');

        expect($object->extensions())->toBeEmpty();

        $object = $object->addExtension($extension1);

        expect($object->extensions())->toHaveCount(1)
            ->and($object->extensions()[0])->equals($extension1)->toBeTrue();

        $result = $object->getExtension($extension1->name());

        expect($result)->equals($extension1)->toBeTrue();

        $object = $object->removeExtension($extension1->name());

        expect($object->extensions())->toBeEmpty();

        $extension2 = Extension::create('x-', 'value2');

        $object = $object->addExtension($extension1, $extension2);

        $result = $object->getExtension($extension2->name());

        expect($result)->equals($extension2)->toBeTrue();

        $object = $object->removeExtension($extension2->name());

        expect($object->extensions())->toHaveCount(1)
            ->and($object->extensions()[0])->equals($extension1)->toBeTrue();

        $object = $object->removeExtension($extension1->name());

        expect($object->extensions())->toBeEmpty();
    });

    it('serializes its extensions', function (): void {
        $object = ExtensibleObjectFake::create();
        $extension1 = Extension::create('x-test', 'value');
        $extension2 = Extension::create('x-foo', 'bar');
        $object = $object->addExtension($extension1, $extension2);

        $result = $object->asArray();

        expect($result)->toBe([
            'x-test' => 'value',
            'x-foo' => 'bar',
        ]);
    });

    it('should throw an exception if property does not exist', function (): void {
        $extensibleObjectFake = ExtensibleObjectFake::create();

        expect(static fn () => $extensibleObjectFake->nonExistingProperty)
            ->toThrow(PropertyDoesNotExistException::class);
    });
})->covers(ExtensibleObject::class);
