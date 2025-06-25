<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleKeyCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Collections\Headers\HeaderEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Collections\Headers\Headers;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleKeyCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class Encoding extends ExtensibleObject implements SimpleKeyCreator
{
    use SimpleKeyCreatorTrait;

    private string|null $contentType = null;
    private Headers|null $headers = null;
    private string|null $style = null;
    private bool|null $explode = null;
    private bool|null $allowReserved = null;

    public function contentType(string|null $contentType): static
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

    public function style(string|null $style): static
    {
        $clone = clone $this;

        $clone->style = $style;

        return $clone;
    }

    public function explode(bool|null $explode = true): static
    {
        $clone = clone $this;

        $clone->explode = $explode;

        return $clone;
    }

    public function allowReserved(bool|null $allowReserved = true): static
    {
        $clone = clone $this;

        $clone->allowReserved = $allowReserved;

        return $clone;
    }

    protected function toArray(): array
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
