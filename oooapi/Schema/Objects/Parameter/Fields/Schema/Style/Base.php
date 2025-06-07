<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Explode;

abstract class Base implements Style
{
    private Explode|null $explode = null;

    abstract protected function value(): string;

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

        $clone->explode = Explode::yes();

        return $clone;
    }

    public function toArray(): array
    {
        return [
            'style' => $this->value(),
            'explode' => $this->explode,
        ];
    }
}
