<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style;

abstract class Base implements Style
{
    private true|null $explode = null;

    final protected function __construct()
    {
    }

    final public static function create(): static
    {
        return new static();
    }

    final public function explode(): static
    {
        $clone = clone $this;

        $clone->explode = true;

        return $clone;
    }

    public function toArray(): array
    {
        return [
            'style' => $this->value(),
            'explode' => $this->explode,
        ];
    }

    abstract protected function value(): string;
}
