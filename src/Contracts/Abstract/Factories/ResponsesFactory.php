<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses;

abstract class ResponsesFactory
{
    abstract public function build(): Responses;
}
