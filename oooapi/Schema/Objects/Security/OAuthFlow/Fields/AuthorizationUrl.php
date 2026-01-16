<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\OAuthFlow\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Validator;

/**
 * The authorization URL for OAuth2 flows.
 *
 * REQUIRED for implicit and authorizationCode flows. MUST be a valid URL.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#oauth-flow-object
 */
final readonly class AuthorizationUrl extends StringField
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
