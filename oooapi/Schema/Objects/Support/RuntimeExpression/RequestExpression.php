<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression;

/**
 * Represents a Request runtime expression ($request.{source}).
 * This expression refers to a value from the request.
 */
abstract readonly class RequestExpression extends RuntimeExpressionAbstract
{
    public const PREFIX = '$request.';

    /**
     * Create a new Request expression.
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
            throw new \InvalidArgumentException(sprintf('Request expression must start with "%s", got "%s"', self::PREFIX, $expression));
        }
    }

    /**
     * Get the source part of the expression.
     */
    public function getSource(): string
    {
        return substr($this->value(), strlen(self::PREFIX));
    }
}
