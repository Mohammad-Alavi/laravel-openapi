<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable;

abstract class ReusableSchemaFactory extends Reusable
{
    final protected static function componentNamespace(): string
    {
        return '/schemas';
    }

    abstract public function build(): JSONSchema;
}
