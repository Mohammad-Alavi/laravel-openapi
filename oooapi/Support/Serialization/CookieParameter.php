<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\Cookie;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\Form;

final class CookieParameter extends SchemaSerialized
{
    public static function create(
        JSONSchema|SchemaFactory $jsonSchema,
        Cookie|Form|null $style = null,
    ): self {
        return new self(
            $jsonSchema,
            $style,
        );
    }
}
