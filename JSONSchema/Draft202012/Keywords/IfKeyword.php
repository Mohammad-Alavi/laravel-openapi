<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

final readonly class IfKeyword implements Keyword
{
    private function __construct(
        private Descriptor $builder,
    ) {
    }

    public static function create(Descriptor $builder): self
    {
        return new self($builder);
    }

    public static function name(): string
    {
        return 'if';
    }

    public function value(): Descriptor
    {
        return $this->builder;
    }
}
