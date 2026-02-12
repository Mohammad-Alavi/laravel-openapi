<?php

namespace MohammadAlavi\LaravelRulesToSchema\Contracts;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

interface HasJsonSchema
{
    public function toJsonSchema(string $attribute): LooseFluentDescriptor;
}
