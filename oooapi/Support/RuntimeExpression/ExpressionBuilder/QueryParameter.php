<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\ExpressionBuilder;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\RuntimeExpressionAbstract;

final readonly class QueryParameter
{
    private function __construct(
        private string $name,
        private string|RuntimeExpressionAbstract $value,
    ) {
    }

    public static function create(string $name, string|RuntimeExpressionAbstract $value): self
    {
        if ($value instanceof RuntimeExpressionAbstract) {
            $value = $value->embeddable();
        }

        return new self($name, trim($value));
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(): string
    {
        return (string) $this->value;
    }
}
