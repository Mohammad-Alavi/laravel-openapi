<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression;

/**
 * Represents a Request runtime expression ($request.{source}).
 * This expression refers to a value from the request.
 */
abstract readonly class RequestExpression extends RuntimeExpressionAbstract
{
    public const PREFIX = '$request.';

    /**
     * Get the source part of the expression.
     */
    public function getSource(): string
    {
        return substr($this->value(), strlen(self::PREFIX));
    }

    /**
     * Validate that the expression is valid.
     */
    protected function validateExpression(string $expression): void
    {
        if (!str_starts_with($expression, self::PREFIX)) {
            throw new \InvalidArgumentException(sprintf('Request expression must start with "%s", got "%s"', self::PREFIX, $expression));
        }
    }
}
