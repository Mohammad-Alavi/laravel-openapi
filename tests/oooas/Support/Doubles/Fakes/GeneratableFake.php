<?php

namespace Tests\oooas\Support\Doubles\Fakes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Generatable;

class GeneratableFake extends Generatable
{
    public function toArray(): array
    {
        return [];
    }
}
