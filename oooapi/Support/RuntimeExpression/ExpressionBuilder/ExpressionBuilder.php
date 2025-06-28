<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\ExpressionBuilder;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\ExpressionBuilder\PathParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\ExpressionBuilder\QueryParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\RuntimeExpressionAbstract;

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

    public function appendPathParam(PathParameter $value): self
    {
        $this->pathParameters[] = $value;

        return $this;
    }

    public function appendQueryParam(QueryParameter $value): self
    {
        $this->queryParameters[] = $value;

        return $this;
    }

    public function append(string $value): self
    {
        $this->value .= $value;

        return $this;
    }

    public function prependPathParam(PathParameter $value): self
    {
        array_unshift($this->pathParameters, $value);

        return $this;
    }

    public function prependQueryParam(QueryParameter $value): self
    {
        array_unshift($this->queryParameters, $value);

        return $this;
    }

    public function prepend(string $value): self
    {
        $this->value = $value . $this->value;

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
