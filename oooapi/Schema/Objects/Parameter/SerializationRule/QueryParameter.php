<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\DeepObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\Form;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\PipeDelimited;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\SpaceDelimited;

final readonly class QueryParameter extends SchemaSerialized
{
    public static function create(
        JSONSchema|SchemaFactory $jsonSchema,
        DeepObject|Form|PipeDelimited|SpaceDelimited|null $style = null,
        ExampleEntry ...$exampleEntry,
    ): self {
        return new self(
            $jsonSchema,
            $style,
            ...$exampleEntry,
        );
    }
}
