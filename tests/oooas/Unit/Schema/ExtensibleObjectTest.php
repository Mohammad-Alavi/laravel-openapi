<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extension;
use Tests\oooas\Support\Doubles\Fakes\ExtensibleObjectFake;

describe(class_basename(ExtensibleObject::class), function (): void {
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

        $result = $object->unserializeToArray();

        expect($result)->toBe([
            'x-test' => 'value',
            'x-foo' => 'bar',
        ]);
    });
})->covers(ExtensibleObject::class);
