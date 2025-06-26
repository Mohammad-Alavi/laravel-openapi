<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression;

/**
 * Represents a URL runtime expression ($url).
 * This expression refers to the request URL.
 */
final readonly class URLExpression extends RuntimeExpressionAbstract
{
    private const EXPRESSION = '$url';

    private function __construct(
        string $value = self::EXPRESSION,
    ) {
        parent::__construct($value);
    }

    /**
     * Create a new URL expression.
     */
    public static function create(string $value = self::EXPRESSION): static
    {
        return new self($value);
    }

    /**
     * Validate that the expression is valid.
     */
    protected function validateExpression(string $expression): void
    {
        if (self::EXPRESSION !== $expression) {
            throw new \InvalidArgumentException(sprintf('URL expression must be "%s", got "%s"', self::EXPRESSION, $expression));
        }
    }
}
