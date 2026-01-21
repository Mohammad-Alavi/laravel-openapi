<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;

/**
 * XML prefix for the element name.
 *
 * The prefix to be used for the name when serializing to XML.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#xml-object
 */
final readonly class Prefix extends StringField
{
    private function __construct(
        private string $value,
    ) {
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
