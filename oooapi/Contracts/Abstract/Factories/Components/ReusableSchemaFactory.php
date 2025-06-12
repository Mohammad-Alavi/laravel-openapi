<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Components\SchemaFactory;

abstract class ReusableSchemaFactory extends Reusable implements SchemaFactory
{
    final protected static function componentNamespace(): string
    {
        return '/schemas';
    }
}
