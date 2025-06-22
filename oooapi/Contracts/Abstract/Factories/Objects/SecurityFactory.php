<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ObjectFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;

abstract class SecurityFactory extends ObjectFactory
{
    abstract public function object(): Security;
}
