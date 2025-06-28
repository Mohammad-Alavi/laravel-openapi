<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\RuntimeExpressionAbstract;

/**
 * Represents a StatusCode runtime expression ($statusCode).
 * This expression refers to the HTTP status code of the response.
 */
final readonly class StatusCodeExpression extends RuntimeExpressionAbstract
{
    private const EXPRESSION = '$statusCode';

    /**
     * Create a new StatusCode expression.
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
            throw new \InvalidArgumentException(sprintf('StatusCode expression must be "%s", got "%s"', self::EXPRESSION, $expression));
        }
    }
}
