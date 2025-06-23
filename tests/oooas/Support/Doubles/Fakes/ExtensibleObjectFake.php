<?php

namespace Tests\oooas\Support\Doubles\Fakes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;

class ExtensibleObjectFake extends ExtensibleObject implements SimpleCreator
{
    use SimpleCreatorTrait;

    protected function toArray(): array
    {
        return [];
    }
}
