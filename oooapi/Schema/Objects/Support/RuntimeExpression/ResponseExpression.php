<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression;

/**
 * Represents a Response runtime expression ($response.{source}).
 * This expression refers to a value from the response.
 */
abstract readonly class ResponseExpression extends RuntimeExpressionAbstract
{
    public const PREFIX = '$response.';

    /**
     * Create a new Response expression.
     */
    protected function __construct(
        string $value,
    ) {
        parent::__construct($value);
    }

    /**
     * Validate that the expression is valid.
     */
    protected function validateExpression(string $expression): void
    {
        if (!str_starts_with($expression, self::PREFIX)) {
            throw new \InvalidArgumentException(sprintf('Response expression must start with "%s", got "%s"', self::PREFIX, $expression));
        }
    }

    /**
     * Get the source part of the expression.
     */
    protected function getSource(): string
    {
        return substr($this->value(), strlen(self::PREFIX));
    }
}
