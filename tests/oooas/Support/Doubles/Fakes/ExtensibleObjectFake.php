<?php

namespace Tests\oooas\Support\Doubles\Fakes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;

final class ExtensibleObjectFake extends ExtensibleObject
{
    public static function create(): self
    {
        return new self();
    }

    public function toArray(): array
    {
        return [];
    }
}
