<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\Label;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\Matrix;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\Simple;

final class PathParameter extends SchemaSerialized
{
    public static function create(
        JSONSchema|SchemaFactory $jsonSchema,
        Label|Matrix|Simple|null $style = null,
    ): self {
        return new self(
            $jsonSchema,
            $style,
        );
    }
}
