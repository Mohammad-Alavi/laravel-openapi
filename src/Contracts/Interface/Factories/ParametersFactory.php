<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Collections\Parameters;

interface ParametersFactory
{
    public function build(): Parameters;
}
