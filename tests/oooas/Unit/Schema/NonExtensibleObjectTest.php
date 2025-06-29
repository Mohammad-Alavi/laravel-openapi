<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Generatable;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\NonExtensibleObject;
use Tests\oooas\Support\Doubles\Fakes\NonExtensibleObjectFake;

describe(class_basename(NonExtensibleObject::class), function (): void {
    it('can be created', function (): void {
        expect(NonExtensibleObjectFake::class)
            ->toExtend(Generatable::class);
    });
})->covers(NonExtensibleObject::class);
