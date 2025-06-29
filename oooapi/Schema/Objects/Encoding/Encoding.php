<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Encoding;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Headers\HeaderEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Headers\Headers;

final class Encoding extends ExtensibleObject
{
    private string|null $contentType = null;
    private Headers|null $headers = null;
    private string|null $style = null;
    private bool|null $explode = null;
    private bool|null $allowReserved = null;

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

    public static function create(): self
    {
        return new self();
    }

    public function style(string|null $style): self
    {
        $clone = clone $this;

        $clone->style = $style;

        return $clone;
    }

    public function explode(bool|null $explode = true): self
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
            'style' => $this->style,
            'explode' => $this->explode,
            'allowReserved' => $this->allowReserved,
        ]);
    }
}
