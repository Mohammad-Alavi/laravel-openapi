<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\StringField;

final readonly class XmlNamespace extends StringField
{
    private function __construct(
        private string $value,
    ) {
        // TODO: Add validation.
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
