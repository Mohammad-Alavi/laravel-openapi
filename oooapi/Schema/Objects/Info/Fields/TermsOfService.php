<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Validator;

/**
 * Terms of Service URL.
 *
 * A URL pointing to the Terms of Service for the API.
 * This MUST be in the form of a URL.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#info-object
 */
final readonly class TermsOfService extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Validator::url($value);
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
