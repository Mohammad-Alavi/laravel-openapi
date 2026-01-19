<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;

interface SecurityFactory
{
    public function build(): Security;
}
