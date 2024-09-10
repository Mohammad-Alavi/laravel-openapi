<?php

namespace MohammadAlavi\LaravelOpenApi\Factories\Component;

use MohammadAlavi\ObjectOrientedOAS\Contracts\SchemaContract;
use MohammadAlavi\ObjectOrientedOAS\Objects\AllOf;
use MohammadAlavi\ObjectOrientedOAS\Objects\AnyOf;
use MohammadAlavi\ObjectOrientedOAS\Objects\Not;
use MohammadAlavi\ObjectOrientedOAS\Objects\OneOf;
use MohammadAlavi\ObjectOrientedOAS\Objects\Schema;
use MohammadAlavi\LaravelOpenApi\Concerns\Referencable;

abstract class SchemaFactory
{
    use Referencable;

    /**
     * @return AllOf|OneOf|AnyOf|Not|Schema
     */
    abstract public function build(): SchemaContract;
}
