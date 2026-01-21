<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\RuntimeExpressionAbstract;

/**
 * Callback Object.
 *
 * A map of possible out-of-band callbacks related to the parent operation.
 * The key is a unique identifier for the Callback Object. Each value is a
 * Path Item Object describing a callback request and expected responses.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#callback-object
 */
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
        if ($expression instanceof RuntimeExpressionAbstract) {
            $expression = $expression->embeddable();
        }

        return new self($expression, $pathItem, $name);
    }

    public function name(): string
    {
        return when(blank($this->name), class_basename($this), $this->name);
    }

    public function toArray(): array
    {
        return Arr::filter([
            (string) $this->expression => $this->pathItem,
        ]);
    }
}
