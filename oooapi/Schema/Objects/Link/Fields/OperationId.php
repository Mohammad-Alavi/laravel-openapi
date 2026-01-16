<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use Webmozart\Assert\Assert;

/**
 * Operation ID for Link Object.
 *
 * The name of an existing, resolvable OAS operation, as defined with a unique
 * operationId. This field is mutually exclusive of the operationRef field.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#link-object
 */
final readonly class OperationId extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Assert::notEmpty($value, 'Operation ID cannot be empty.');
        Assert::regex(
            $value,
            '/^[a-zA-Z0-9._-]+$/',
            sprintf('Operation ID "%s" contains invalid characters. Use alphanumeric, dots, underscores, or hyphens.', $value),
        );
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
