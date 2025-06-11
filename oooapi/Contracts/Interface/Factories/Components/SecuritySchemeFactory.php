<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ComponentFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme;

interface SecuritySchemeFactory extends ComponentFactory
{
    public function build(): SecurityScheme;
}
