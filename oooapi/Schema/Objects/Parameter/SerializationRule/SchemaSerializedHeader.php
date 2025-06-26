<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\Simple;

final readonly class SchemaSerializedHeader extends SchemaSerialized
{
    public static function create(
        JSONSchema $jsonSchema,
        Simple|null $style = null,
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
