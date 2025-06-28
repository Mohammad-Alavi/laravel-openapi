<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\Sources;

/**
 * Represents a path reference in a runtime expression (path.{name}).
 */
final readonly class PathReference
{
    public const PREFIX = 'path.';

    private function __construct(
        private string $name,
    ) {
        $this->validateName($name);
    }

    /**
     * Create a new path reference.
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
