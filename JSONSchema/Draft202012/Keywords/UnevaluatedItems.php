<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

final readonly class UnevaluatedItems implements Keyword
{
    private function __construct(
        private Descriptor $descriptor,
    ) {
    }

    public static function create(Descriptor $descriptor): self
    {
        return new self($descriptor);
    }

    public static function name(): string
    {
        return 'unevaluatedItems';
    }

    public function jsonSerialize(): Descriptor
    {
        return $this->value();
    }

    public function value(): Descriptor
    {
        return $this->descriptor;
    }
}
