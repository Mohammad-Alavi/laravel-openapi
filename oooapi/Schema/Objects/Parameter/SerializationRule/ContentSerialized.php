<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Content\ContentEntry;

final readonly class ContentSerialized implements SerializationRule
{
    private function __construct(
        private ContentEntry $contentEntry,
    ) {
    }

    public static function create(ContentEntry $contentEntry): self
    {
        return new self($contentEntry);
    }

    public function toArray(): array
    {
        return [
            'content' => $this->contentEntry,
        ];
    }
}
