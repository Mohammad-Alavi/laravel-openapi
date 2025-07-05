<?php

namespace Tests\oooas\Support\Doubles\Fakes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Generatable;

class NonExtensibleObjectFake extends Generatable
{
    public function toArray(): array
    {
        return [];
    }
}
