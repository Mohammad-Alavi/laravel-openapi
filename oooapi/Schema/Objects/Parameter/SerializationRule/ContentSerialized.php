<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Content\ContentEntry;

final readonly class ContentSerialized implements SerializationRule
{
    private function __construct(
        private ContentEntry $entry,
    ) {
    }

    public static function create(ContentEntry $entry): self
    {
        return new self($entry);
    }

    public function toArray(): array
    {
        return [
            'content' => $this->entry,
        ];
    }
}
