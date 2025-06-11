<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Collections;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ComponentFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;

interface SecurityFactory extends ComponentFactory
{
    public function build(): Security;
}
