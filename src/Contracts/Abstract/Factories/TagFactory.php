<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

abstract class TagFactory
{
    abstract public function build(): Tag;
}
