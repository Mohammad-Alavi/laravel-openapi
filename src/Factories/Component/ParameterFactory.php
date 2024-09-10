<?php

namespace MohammadAlavi\LaravelOpenApi\Factories\Component;

use MohammadAlavi\ObjectOrientedOAS\Objects\Parameter;
use MohammadAlavi\LaravelOpenApi\Concerns\Referencable;

abstract class ParameterFactory
{
    use Referencable;

    /**
     * @return Parameter[]
     */
    abstract public function build(): array;
}
