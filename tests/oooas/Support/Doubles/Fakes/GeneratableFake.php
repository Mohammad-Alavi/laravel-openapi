<?php

namespace Tests\oooas\Support\Doubles\Fakes;

use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Generatable;

class GeneratableFake extends Generatable
{
    protected function toArray(): array
    {
        return [];
    }
}
