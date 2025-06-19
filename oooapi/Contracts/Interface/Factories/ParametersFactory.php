<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ComponentFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameters;

interface ParametersFactory extends ComponentFactory
{
    public function build(): Parameters;
}
