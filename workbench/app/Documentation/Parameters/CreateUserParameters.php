<?php

namespace Workbench\App\Documentation\Parameters;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ParametersFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;

class CreateUserParameters implements ParametersFactory
{
    public function build(): Parameters
    {
        return Parameters::create();
    }
}
