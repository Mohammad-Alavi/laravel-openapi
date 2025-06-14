<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

final readonly class AdditionalProperties implements Keyword
{
    private function __construct(
        private JSONSchema|bool $schema,
    ) {
    }

    public static function create(JSONSchema|bool $schema): self
    {
        return new self($schema);
    }

    public static function name(): string
    {
        return 'additionalProperties';
    }

    public function jsonSerialize(): JSONSchema|bool
    {
        return $this->value();
    }

    public function value(): JSONSchema|bool
    {
        return $this->schema;
    }
}
