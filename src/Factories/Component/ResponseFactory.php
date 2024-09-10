<?php

namespace MohammadAlavi\LaravelOpenApi\Factories\Component;

use MohammadAlavi\LaravelOpenApi\Concerns\Referencable;
use MohammadAlavi\ObjectOrientedOAS\Objects\Response;

abstract class ResponseFactory
{
    use Referencable;

    abstract public function build(): Response;
}
