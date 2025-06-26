<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\NonExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Generatable;
use Tests\oooas\Support\Doubles\Fakes\NonExtensibleObjectFake;

describe('NonExtensibleObject', function (): void {
    it('can be created', function (): void {
        expect(NonExtensibleObjectFake::class)
            ->toExtend(Generatable::class);
    });
})->covers(NonExtensibleObject::class);
