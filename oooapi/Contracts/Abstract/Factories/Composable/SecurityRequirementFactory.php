<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Composable;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ComposableFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;

/**
 * @template T of SecurityRequirement
 *
 * @extends ComposableFactory<T>
 */
abstract class SecurityRequirementFactory extends ComposableFactory
{
    abstract public function object(): SecurityRequirement;
}
