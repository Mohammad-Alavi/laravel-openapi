<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\StringField;

final readonly class OpenAPI extends StringField
{
    private function __construct(
        private string $value,
    ) {
    }

    public static function v310(): self
    {
        return new self('3.1.0');
    }

    public static function v311(): self
    {
        return new self('3.1.1');
    }

    public function value(): string
    {
        return $this->value;
    }
}
