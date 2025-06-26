<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\StringField;

/**
 * Runtime expressions allow defining values based on information that will only be available
 * within the HTTP message in an actual API call. This mechanism is used by Link Objects and Callback Objects.
 */
abstract readonly class RuntimeExpressionAbstract extends StringField
{
    /**
     * Create a new runtime expression.
     */
    protected function __construct(
        private string $value,
    ) {
        $this->validateExpression($value);
    }

    /**
     * Validate that the expression is valid according to the ABNF syntax.
     */
    protected function validateExpression(string $expression): void
    {
        // Base validation will be implemented in child classes
    }

    /**
     * Get the expression value.
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Create a new instance of the runtime expression.
     */
    public static function create(string $value): static
    {
        return new static($value);
    }
}
