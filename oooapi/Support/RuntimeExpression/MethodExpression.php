<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression;

/**
 * Represents a Method runtime expression ($method).
 * This expression refers to the HTTP method of the request.
 */
final readonly class MethodExpression extends RuntimeExpressionAbstract
{
    private const EXPRESSION = '$method';

    /**
     * Create a new Method expression.
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
            throw new \InvalidArgumentException(sprintf('Method expression must be "%s", got "%s"', self::EXPRESSION, $expression));
        }
    }
}
