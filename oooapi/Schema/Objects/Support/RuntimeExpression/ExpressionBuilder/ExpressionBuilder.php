<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\ExpressionBuilder;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\RuntimeExpressionAbstract;

final class ExpressionBuilder
{
    private array $pathParameters = [];
    private array $queryParameters = [];

    private function __construct(
        private string $value,
    ) {
    }

    public static function of(string|RuntimeExpressionAbstract $value): self
    {
        return new self(trim((string) $value));
    }

    public function append(string|PathParameter|QueryParameter $value): self
    {
        if ($value instanceof PathParameter) {
            $this->pathParameters[] = $value;
        } elseif ($value instanceof QueryParameter) {
            $this->queryParameters[] = $value;
        } else {
            $this->value .= $value;
        }

        return $this;
    }

    public function prepend(string|PathParameter|QueryParameter $value): self
    {
        if ($value instanceof PathParameter) {
            array_unshift($this->pathParameters, $value);
        } elseif ($value instanceof QueryParameter) {
            array_unshift($this->queryParameters, $value);
        } else {
            $this->value = $value . $this->value;
        }

        return $this;
    }

    public function value(): string
    {
        $path = implode('/', array_map(
            static function (PathParameter $param) {
                return $param->name();
            },
            $this->pathParameters,
        ));
        $query = implode('&', array_map(
            static function (QueryParameter $param) {
                return $param->name() . '=' . $param->value();
            },
            $this->queryParameters,
        ));

        return trim($this->value . ($path ? '/' . $path : '') . ($query ? '?' . $query : ''));
    }
}
