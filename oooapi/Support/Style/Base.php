<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Style;

abstract class Base implements Style
{
    final protected function __construct()
    {
    }

    final public static function create(): static
    {
        return new static();
    }

    public function jsonSerialize(): array|null
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'style' => $this->value(),
        ];
    }

    /**
     * Returns the style value string as defined in the OpenAPI specification.
     */
    abstract protected function value(): string;
}
