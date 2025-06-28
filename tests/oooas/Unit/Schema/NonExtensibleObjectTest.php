<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\NonExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Generatable;
use Tests\oooas\Support\Doubles\Fakes\NonExtensibleObjectFake;

describe('NonExtensibleObject', function (): void {
    it('can be created', function (): void {
        expect(NonExtensibleObjectFake::class)
            ->toExtend(Generatable::class);
    });
})->covers(NonExtensibleObject::class);
