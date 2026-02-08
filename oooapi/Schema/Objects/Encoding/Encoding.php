<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Encoding;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\Fields\Encoding\Encoding as EncodingMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\Fields\Encoding\EncodingEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Headers\HeaderEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Headers\Headers;

/**
 * Encoding Object.
 *
 * A single encoding definition applied to a single schema property.
 * This object provides additional information about how a property should
 * be serialized when using application/x-www-form-urlencoded or multipart content.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#encoding-object
 */
final class Encoding extends ExtensibleObject
{
    private string|null $contentType = null;
    private Headers|null $headers = null;
    private EncodingMap|null $encoding = null;
    /** @var self[]|null */
    private array|null $prefixEncoding = null;
    private self|null $itemEncoding = null;
    private string|null $style = null;
    private bool|null $explode = null;
    private bool|null $allowReserved = null;

    public static function create(): self
    {
        return new self();
    }

    public function contentType(string $contentType): self
    {
        $clone = clone $this;

        $clone->contentType = $contentType;

        return $clone;
    }

    public function headers(HeaderEntry ...$headerEntry): self
    {
        $clone = clone $this;

        $clone->headers = Headers::create(...$headerEntry);

        return $clone;
    }

    public function encoding(EncodingEntry ...$encodingEntry): self
    {
        $clone = clone $this;

        $clone->encoding = EncodingMap::create(...$encodingEntry);

        return $clone;
    }

    public function prefixEncoding(self ...$encoding): self
    {
        $clone = clone $this;

        $clone->prefixEncoding = $encoding;

        return $clone;
    }

    public function itemEncoding(self $encoding): self
    {
        $clone = clone $this;

        $clone->itemEncoding = $encoding;

        return $clone;
    }

    public function style(string $style): self
    {
        $clone = clone $this;

        $clone->style = $style;

        return $clone;
    }

    public function explode(bool $explode = true): self
    {
        $clone = clone $this;

        $clone->explode = $explode;

        return $clone;
    }

    public function allowReserved(): self
    {
        $clone = clone $this;

        $clone->allowReserved = true;

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'contentType' => $this->contentType,
            'headers' => $this->headers,
            'encoding' => $this->encoding,
            'prefixEncoding' => $this->prefixEncoding,
            'itemEncoding' => $this->itemEncoding,
            'style' => $this->style,
            'explode' => $this->explode,
            'allowReserved' => $this->allowReserved,
        ]);
    }
}
