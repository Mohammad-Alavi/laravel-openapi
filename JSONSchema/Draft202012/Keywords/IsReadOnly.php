<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

final readonly class IsReadOnly implements Keyword
{
    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    public static function name(): string
    {
        return 'readOnly';
    }

    public function jsonSerialize(): true
    {
        return $this->value();
    }

    public function value(): true
    {
        return true;
    }
}
