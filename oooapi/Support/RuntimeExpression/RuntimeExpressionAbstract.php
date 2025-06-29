<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression;

/**
 * Runtime expressions allow defining values based on information that will only be available
 * within the HTTP message in an actual API call. This mechanism is used by Link Objects and Callback Objects.
 */
abstract readonly class RuntimeExpressionAbstract implements \JsonSerializable, \Stringable
{
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

    public function embeddable(): string
    {
        return '{' . $this->__toString() . '}';
    }

    final public function __toString(): string
    {
        return $this->value();
    }

    /**
     * Get the expression value.
     */
    final public function value(): string
    {
        return $this->value;
    }

    final public function jsonSerialize(): string
    {
        return (string) $this;
    }
}
