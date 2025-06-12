<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable\ReusableSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Components\SchemaFactory;

abstract class ReusableSchemaFactory extends ReusableSchema implements SchemaFactory
{
    final protected static function componentNamespace(): string
    {
        return '/schemas';
    }
}
