<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\RuntimeExpressionAbstract;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class Callback extends ExtensibleObject
{
    private function __construct(
        private readonly string|RuntimeExpressionAbstract $expression,
        private readonly PathItem $pathItem,
        private readonly string|null $name = null,
    ) {
    }

    public static function create(
        string|RuntimeExpressionAbstract $expression,
        PathItem $pathItem,
        string|null $name = null,
    ): self {
        return new self($expression, $pathItem, $name);
    }

    public function name(): string
    {
        return when(blank($this->name), class_basename($this), $this->name);
    }

    protected function toArray(): array
    {
        return Arr::filter([
            (string) $this->expression => $this->pathItem,
        ]);
    }
}
