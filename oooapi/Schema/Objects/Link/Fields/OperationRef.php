<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use Webmozart\Assert\Assert;

/**
 * Operation Reference for Link Object.
 *
 * A relative or absolute URI reference to an OAS operation. This field is
 * mutually exclusive of the operationId field, and MUST point to an Operation Object.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#link-object
 */
final readonly class OperationRef extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Assert::notEmpty($value, 'Operation reference cannot be empty.');

        // Validate it's a valid URI structure
        $parsed = parse_url($value);
        Assert::notFalse(
            $parsed,
            sprintf('Operation reference "%s" is not a valid URI.', $value),
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
