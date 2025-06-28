<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\Sources;

/**
 * Represents a query reference in a runtime expression (query.{name}).
 */
final readonly class QueryReference
{
    public const PREFIX = 'query.';

    private function __construct(
        private string $name,
    ) {
        $this->validateName($name);
    }

    /**
     * Create a new query reference.
     */
    public static function create(string $name): self
    {
        return new self($name);
    }

    /**
     * Get the name.
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get the full reference string.
     */
    public function toString(): string
    {
        return self::PREFIX . $this->name;
    }

    /**
     * Validate that the name is valid according to the ABNF syntax.
     *
     * name = *( CHAR )
     */
    private function validateName(string $name): void
    {
        if ('' === $name || '0' === $name) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }
    }
}
