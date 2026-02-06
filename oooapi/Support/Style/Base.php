<?php

declare(strict_types=1);

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Style;

/**
 * Base class for parameter serialization styles.
 *
 * Provides common functionality for all style implementations including
 * the style value and explode modifier.
 */
abstract class Base implements Style
{
    private bool|null $explode = null;

    final protected function __construct()
    {
    }

    final public static function create(): static
    {
        return new static();
    }

    /**
     * When true, generates separate parameters for each value of array or object.
     *
     * Default behavior varies by style:
     * - form, cookie: explode defaults to true
     * - all others: explode defaults to false
     */
    final public function explode(bool $explode = true): static
    {
        $clone = clone $this;

        $clone->explode = $explode;

        return $clone;
    }

    public function jsonSerialize(): array|null
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'style' => $this->value(),
            'explode' => $this->explode,
        ];
    }

    /**
     * Returns the style value string as defined in the OpenAPI specification.
     */
    abstract protected function value(): string;
}
