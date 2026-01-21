<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;

/**
 * Unique string identifying the operation.
 *
 * The id MUST be unique among all operations described in the API.
 * The operationId value is case-sensitive and is used by various tools.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#operation-object
 */
final readonly class OperationId extends StringField
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
