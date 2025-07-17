<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\Form;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;

final readonly class CookieParameter extends SchemaSerialized
{
    public static function create(
        JSONSchema|SchemaFactory $jsonSchema,
        Form|null $style = null,
        ExampleEntry ...$exampleEntry,
    ): self {
        return new self(
            $jsonSchema,
            $style,
            ...$exampleEntry,
        );
    }
}
