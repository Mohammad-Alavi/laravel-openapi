<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Validator;

/**
 * Path Item Object $ref field.
 *
 * Allows for a referenced definition of a path item. The referenced structure
 * MUST be a Path Item Object.
 *
 * Note: This is distinct from the Reference Object's $ref. The Path Item $ref
 * can coexist with other Path Item fields, though the behavior when both are
 * present is undefined per the spec. The OAS spec notes this behavior may
 * change in future versions to align with Reference Object behavior.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#path-item-object
 */
final readonly class Ref extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Validator::uri($value);
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
