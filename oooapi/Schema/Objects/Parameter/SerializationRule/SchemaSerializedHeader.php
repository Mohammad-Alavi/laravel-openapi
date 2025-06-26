<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\Simple;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Examples\ExampleEntry;

final readonly class SchemaSerializedHeader extends SchemaSerialized
{
    public static function create(
        JSONSchema $jsonSchema,
        Simple|null $style = null,
        ExampleEntry ...$exampleEntry,
    ): self {
        return new self(
            $jsonSchema,
            $style,
            ...$exampleEntry,
        );
    }
}
