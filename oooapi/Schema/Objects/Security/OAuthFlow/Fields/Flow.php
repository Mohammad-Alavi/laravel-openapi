<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\OAuthFlow\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;

final readonly class Flow extends StringField
{
    /**
     * Create a new flow.
     */
    private function __construct(
        private string $value,
    ) {
    }

    public static function implicit(): self
    {
        return new self('implicit');
    }

    public static function password(): self
    {
        return new self('password');
    }

    public static function clientCredentials(): self
    {
        return new self('clientCredentials');
    }

    public static function authorizationCode(): self
    {
        return new self('authorizationCode');
    }

    /**
     * Get the flow value.
     */
    public function value(): string
    {
        return $this->value;
    }
}
