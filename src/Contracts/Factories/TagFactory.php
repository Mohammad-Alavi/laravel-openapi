<?php

namespace MohammadAlavi\LaravelOpenApi\Contracts\Factories;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;

interface TagFactory
{
    public function build(): Tag;
}
