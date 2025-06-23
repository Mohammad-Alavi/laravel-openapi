<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\NonExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Generatable;
use Tests\oooas\Support\Doubles\Fakes\NonExtensibleObjectFake;

describe('NonExtensibleObject', function (): void {
    it('can be created', function (): void {
        expect(NonExtensibleObjectFake::class)
            ->toExtend(Generatable::class);
    });
})->covers(NonExtensibleObject::class);
