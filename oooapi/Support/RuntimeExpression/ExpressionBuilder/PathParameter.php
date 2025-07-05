<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\ExpressionBuilder;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\RuntimeExpressionAbstract;

final readonly class PathParameter
{
    private function __construct(
        private string|RuntimeExpressionAbstract $name,
    ) {
    }

    public static function create(string|RuntimeExpressionAbstract $name): self
    {
        if ($name instanceof RuntimeExpressionAbstract) {
            $name = $name->embeddable();
        }

        return new self(trim($name));
    }

    public function name(): string
    {
        return (string) $this->name;
    }
}
