<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ComponentFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;

interface ParameterFactory extends ComponentFactory
{
    public function build(): Parameter;
}
