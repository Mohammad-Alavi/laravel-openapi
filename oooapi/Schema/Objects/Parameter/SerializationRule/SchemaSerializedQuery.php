<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\DeepObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\Form;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\PipeDelimited;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\SpaceDelimited;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;

final readonly class SchemaSerializedQuery extends SchemaSerialized
{
    public static function create(
        JSONSchema $jsonSchema,
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
