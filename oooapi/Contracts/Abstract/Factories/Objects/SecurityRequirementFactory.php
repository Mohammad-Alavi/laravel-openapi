<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ObjectFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;

abstract class SecurityRequirementFactory extends ObjectFactory
{
    abstract public function object(): SecurityRequirement;
}
