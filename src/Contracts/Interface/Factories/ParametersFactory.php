<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;

interface ParametersFactory
{
    public function build(): Parameters;
}
