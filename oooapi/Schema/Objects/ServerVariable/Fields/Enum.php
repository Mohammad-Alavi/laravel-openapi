<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields;

use Webmozart\Assert\Assert;

/**
 * Enumeration of allowed values for a server variable.
 *
 * An enumeration of string values to be used if the substitution options are
 * from a limited set. The array MUST NOT be empty.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#server-variable-object
 */
final readonly class Enum implements \JsonSerializable
{
    private function __construct(
        private array $values,
    ) {
        Assert::notEmpty($values);
    }

    public static function create(string ...$value): self
    {
        return new self($value);
    }

    /** @return string[] $values */
    public function values(): array
    {
        return $this->values;
    }

    /** @return string[] $values */
    public function jsonSerialize(): array
    {
        return $this->values;
    }
}
