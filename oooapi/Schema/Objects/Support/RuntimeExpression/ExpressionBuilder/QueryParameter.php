<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\ExpressionBuilder;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\RuntimeExpressionAbstract;

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
            $value = '{' . $value->value() . '}';
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
