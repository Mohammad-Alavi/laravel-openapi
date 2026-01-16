<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "contentMediaType" keyword specifies the MIME type of string content.
 *
 * Used to indicate the media type when the string contains encoded content.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-8.4
 */
final readonly class ContentMediaType implements Keyword
{
    private function __construct(
        private string $mediaType,
    ) {
    }

    public static function create(string $mediaType): self
    {
        return new self($mediaType);
    }

    public static function name(): string
    {
        return 'contentMediaType';
    }

    public function jsonSerialize(): string
    {
        return $this->value();
    }

    public function value(): string
    {
        return $this->mediaType;
    }
}
