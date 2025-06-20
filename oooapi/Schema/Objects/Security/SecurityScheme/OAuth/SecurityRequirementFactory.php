<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;

abstract readonly class SecurityRequirementFactory implements SimpleCreator
{
    use SimpleCreatorTrait;

    abstract public function build(): SecurityRequirement;
}
