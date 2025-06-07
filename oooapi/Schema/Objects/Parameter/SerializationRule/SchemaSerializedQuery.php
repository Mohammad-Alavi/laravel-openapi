<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\DeepObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\Form;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\PipeDelimited;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style\Styles\SpaceDelimited;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\JSONSchema;

final readonly class SchemaSerializedQuery extends SchemaSerialized
{
    public static function create(
        JSONSchema $jsonSchema,
        DeepObject|Form|PipeDelimited|SpaceDelimited|null $style = null,
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
