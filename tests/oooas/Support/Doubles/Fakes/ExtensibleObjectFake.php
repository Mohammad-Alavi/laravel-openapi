<?php

namespace Tests\oooas\Support\Doubles\Fakes;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;

final class ExtensibleObjectFake extends ExtensibleObject
{
    public static function create(): self
    {
        return new self();
    }

    protected function toArray(): array
    {
        return [];
    }
}
