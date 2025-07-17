<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\Simple;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;

final readonly class HeaderParameter extends SchemaSerialized
{
    public static function create(
        JSONSchema|SchemaFactory $jsonSchema,
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
