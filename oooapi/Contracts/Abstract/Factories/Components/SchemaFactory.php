<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

abstract class SchemaFactory extends ReusableComponent implements JSONSchemaFactory
{
    final protected static function componentNamespace(): string
    {
        return '/schemas';
    }

    final public function build(): JSONSchema
    {
        if (is_a($this, ShouldBeReferenced::class)) {
            return Schema::ref(self::uri());
        }

        return $this->component();
    }

    abstract public function component(): JSONSchema;
}
