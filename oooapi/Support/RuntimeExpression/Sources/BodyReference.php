<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\Sources;

/**
 * Represents a body reference in a runtime expression (body[#{json-pointer}]).
 */
final readonly class BodyReference
{
    public const PREFIX = 'body';
    public const POINTER_PREFIX = '#';

    private function __construct(
        private string $jsonPointer = '',
    ) {
        $this->validateJsonPointer($jsonPointer);
    }

    /**
     * Create a new body reference.
     */
    public static function create(string $jsonPointer = ''): self
    {
        return new self($jsonPointer);
    }

    /**
     * Get the JSON pointer.
     */
    public function jsonPointer(): string
    {
        return $this->jsonPointer;
    }

    /**
     * Get the full reference string.
     */
    public function toString(): string
    {
        if ('' === $this->jsonPointer || '0' === $this->jsonPointer) {
            return self::PREFIX;
        }

        return self::PREFIX . self::POINTER_PREFIX . $this->jsonPointer;
    }

    /**
     * Validate that the JSON pointer is valid according to the ABNF syntax.
     *
     * json-pointer    = *( "/" reference-token )
     * reference-token = *( unescaped / escaped )
     * unescaped       = %x00-2E / %x30-7D / %x7F-10FFFF
     *                 ; %x2F ('/') and %x7E ('~') are excluded from 'unescaped'
     * escaped         = "~" ( "0" / "1" )
     *                 ; representing '~' and '/', respectively
     */
    private function validateJsonPointer(string $jsonPointer): void
    {
        // Empty pointer is valid
        if ('' === $jsonPointer || '0' === $jsonPointer) {
            return;
        }

        // Pointer must start with a slash
        if (!str_starts_with($jsonPointer, '/')) {
            throw new \InvalidArgumentException(sprintf('JSON pointer must start with "/", got "%s"', $jsonPointer));
        }

        // Check for valid escape sequences
        if (preg_match('/~[^01]/', $jsonPointer)) {
            throw new \InvalidArgumentException(sprintf('JSON pointer contains invalid escape sequence: "%s"', $jsonPointer));
        }
    }
}
