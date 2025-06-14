<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\Form;

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
