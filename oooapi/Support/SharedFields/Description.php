<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use Webmozart\Assert\Assert;

/**
 * Description field used across multiple OpenAPI objects.
 *
 * CommonMark syntax MAY be used for rich text representation.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#rich-text-formatting
 */
final readonly class Description extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Assert::notEmpty($value, 'Description cannot be empty.');
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
