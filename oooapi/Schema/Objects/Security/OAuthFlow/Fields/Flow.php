<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\OAuthFlow\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\StringField;

/**
 * Represents the flow type for OAuth2 authentication.
 */
final readonly class Flow extends StringField
{
    /**
     * The implicit flow.
     */
    private const IMPLICIT = 'implicit';

    /**
     * The password flow.
     */
    private const PASSWORD = 'password';

    /**
     * The client credentials flow.
     */
    private const CLIENT_CREDENTIALS = 'clientCredentials';

    /**
     * The authorization code flow.
     */
    private const AUTHORIZATION_CODE = 'authorizationCode';

    /**
     * Create a new flow.
     */
    private function __construct(
        private string $value,
    ) {
        $this->validateFlow($value);
    }

    /**
     * Create a new implicit flow.
     */
    public static function implicit(): self
    {
        return new self(self::IMPLICIT);
    }

    /**
     * Create a new password flow.
     */
    public static function password(): self
    {
        return new self(self::PASSWORD);
    }

    /**
     * Create a new client credentials flow.
     */
    public static function clientCredentials(): self
    {
        return new self(self::CLIENT_CREDENTIALS);
    }

    /**
     * Create a new authorization code flow.
     */
    public static function authorizationCode(): self
    {
        return new self(self::AUTHORIZATION_CODE);
    }

    /**
     * Create a new flow from a string value.
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    /**
     * Get the flow value.
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Validate that the flow is valid.
     */
    private function validateFlow(string $flow): void
    {
        $validFlows = [
            self::IMPLICIT,
            self::PASSWORD,
            self::CLIENT_CREDENTIALS,
            self::AUTHORIZATION_CODE,
        ];

        if (!in_array($flow, $validFlows, true)) {
            throw new \InvalidArgumentException(sprintf('Invalid flow: "%s". Valid flows are: %s', $flow, implode(', ', $validFlows)));
        }
    }
}
