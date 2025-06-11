<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Collections;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ComponentFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ParameterCollection;

interface ParameterCollectionFactory extends ComponentFactory
{
    public function build(): ParameterCollection;
}
