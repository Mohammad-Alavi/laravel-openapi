<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\Form;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\JSONSchema;

final readonly class SchemaSerializedCookie extends SchemaSerialized
{
    public static function create(
        JSONSchema $jsonSchema,
        Form|null $style = null,
        Example|null $example = null,
        Example ...$examples,
    ): self {
        return new self(
            $jsonSchema,
            $style,
            $example,
            $examples,
        );
    }
}
