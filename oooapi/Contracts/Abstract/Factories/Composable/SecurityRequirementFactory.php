<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Composable;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ComposableFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;

abstract class SecurityRequirementFactory extends ComposableFactory
{
    abstract public function object(): SecurityRequirement;
}
