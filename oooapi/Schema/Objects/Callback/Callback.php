<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\RuntimeExpressionAbstract;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class Callback extends ExtensibleObject
{
    private string $key;

    private function __construct(
        private readonly RuntimeExpressionAbstract $expression,
        private readonly PathItem $pathItem,
    ) {
    }

    // TODO: I don't believe callback key is mandatory if callback is reusable/reference
    public static function create(RuntimeExpressionAbstract $expression, PathItem $pathItem, string $key = ''): self
    {
        $instance = new self($expression, $pathItem);

        $instance->key = $key;

        return $instance;
    }

    public function key(): string
    {
        return $this->key;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            (string) $this->expression => $this->pathItem,
        ]);
    }
}
