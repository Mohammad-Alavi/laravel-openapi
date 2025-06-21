<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameters;

interface ParametersFactory
{
    public function build(): Parameters;
}
