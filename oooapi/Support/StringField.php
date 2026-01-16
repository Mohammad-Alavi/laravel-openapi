<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support;

/**
 * Base class for string-valued fields in OpenAPI objects.
 *
 * Provides common functionality for string fields including JSON serialization
 * and string conversion. All OpenAPI string field types should extend this class.
 */
abstract readonly class StringField implements \JsonSerializable, \Stringable
{
    public function __toString(): string
    {
        return $this->value();
    }

    abstract public function value(): string;

    public function jsonSerialize(): string
    {
        return $this->value();
    }
}
