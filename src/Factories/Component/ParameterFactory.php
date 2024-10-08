<?php

namespace MohammadAlavi\LaravelOpenApi\Factories\Component;

use MohammadAlavi\LaravelOpenApi\Concerns\Referencable;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Parameter;

abstract class ParameterFactory
{
    use Referencable;

    /** @return Parameter[] */
    abstract public function build(): array;
}
