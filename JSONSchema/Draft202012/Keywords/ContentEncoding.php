<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "contentEncoding" keyword specifies how string content is encoded.
 *
 * Common values include "base64" or "quoted-printable" for binary data.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-8.3
 */
final readonly class ContentEncoding implements Keyword
{
    private function __construct(
        private string $encoding,
    ) {
    }

    public static function create(string $encoding): self
    {
        return new self($encoding);
    }

    public static function name(): string
    {
        return 'contentEncoding';
    }

    public function jsonSerialize(): string
    {
        return $this->value();
    }

    public function value(): string
    {
        return $this->encoding;
    }
}
