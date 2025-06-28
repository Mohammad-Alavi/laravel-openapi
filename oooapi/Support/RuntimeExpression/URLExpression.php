<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\RuntimeExpressionAbstract;

/**
 * Represents a URL runtime expression ($url).
 * This expression refers to the request URL.
 */
final readonly class URLExpression extends RuntimeExpressionAbstract
{
    private const EXPRESSION = '$url';

    /**
     * Create a new URL expression.
     */
    public static function create(string $value = self::EXPRESSION): self
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
