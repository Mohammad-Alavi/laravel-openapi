<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;

interface ParametersFactory
{
    public function build(): Parameters;
}
