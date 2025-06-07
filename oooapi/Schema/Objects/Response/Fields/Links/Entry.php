<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Links;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;

final readonly class Entry
{
    private function __construct(
        private string $key,
        private Link $link,
    ) {
    }

    public static function create(string $name, Link $link): self
    {
        return new self($name, $link);
    }

    public function value(): array
    {
        return [$this->key => $this->link];
    }
}
