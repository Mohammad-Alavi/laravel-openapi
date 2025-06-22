<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;

interface SecurityFactory
{
    public function object(): Security;
}
