<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\Sources;

/**
 * Represents a header reference in a runtime expression (header.{token}).
 */
final readonly class HeaderReference
{
    public const PREFIX = 'header.';

    private function __construct(
        private string $token,
    ) {
        $this->validateToken($token);
    }

    /**
     * Create a new header reference.
     */
    public static function create(string $token): self
    {
        return new self($token);
    }

    /**
     * Get the token.
     */
    public function token(): string
    {
        return $this->token;
    }

    /**
     * Get the full reference string.
     */
    public function toString(): string
    {
        return self::PREFIX . $this->token;
    }

    /**
     * Validate that the token is valid according to the ABNF syntax.
     *
     * token = 1*tchar
     * tchar = "!" / "#" / "$" / "%" / "&" / "'" / "*" / "+" / "-" / "."
     *       / "^" / "_" / "`" / "|" / "~" / DIGIT / ALPHA
     */
    private function validateToken(string $token): void
    {
        if ('' === $token || '0' === $token) {
            throw new \InvalidArgumentException('Token cannot be empty');
        }

        // Check that the token contains only valid characters
        if (in_array(preg_match('/^[a-zA-Z0-9!#$%&\'*+\-.^_`|~]+$/', $token), [0, false], true)) {
            throw new \InvalidArgumentException(sprintf('Token contains invalid characters: "%s"', $token));
        }
    }
}
