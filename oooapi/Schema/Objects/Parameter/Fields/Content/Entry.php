<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Content;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;

final readonly class Entry
{
    private function __construct(
        private string $name,
        private MediaType $mediaType,
    ) {
    }

    public static function create(string $name, MediaType $mediaType): self
    {
        return new self($name, $mediaType);
    }

    public function value(): array
    {
        return [$this->name => $this->mediaType];
    }
}
