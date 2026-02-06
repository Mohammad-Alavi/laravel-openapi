<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\DeepObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\Form;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\PipeDelimited;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\SpaceDelimited;

final class QueryParameter extends SchemaSerialized
{
    public static function create(
        JSONSchema|SchemaFactory $jsonSchema,
        DeepObject|Form|PipeDelimited|SpaceDelimited|null $style = null,
    ): self {
        return new self(
            $jsonSchema,
            $style,
        );
    }
}
