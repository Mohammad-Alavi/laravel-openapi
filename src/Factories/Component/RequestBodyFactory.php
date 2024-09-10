<?php

namespace MohammadAlavi\LaravelOpenApi\Factories\Component;

use MohammadAlavi\LaravelOpenApi\Concerns\Referencable;
use MohammadAlavi\ObjectOrientedOAS\Objects\RequestBody;

abstract class RequestBodyFactory
{
    use Referencable;

    abstract public function build(): RequestBody;
}
