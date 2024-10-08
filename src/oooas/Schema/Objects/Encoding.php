<?php

namespace MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects;

use MohammadAlavi\LaravelOpenApi\oooas\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

class Encoding extends ExtensibleObject
{
    protected string|null $contentType = null;

    /** @var Header[]|null */
    protected array|null $headers = null;

    protected string|null $style = null;
    protected bool|null $explode = null;
    protected bool|null $allowReserved = null;

    public function contentType(string|null $contentType): static
    {
        $clone = clone $this;

        $clone->contentType = $contentType;

        return $clone;
    }

    public function headers(Header ...$header): static
    {
        $clone = clone $this;

        $clone->headers = [] !== $header ? $header : null;

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
        $headers = [];
        foreach ($this->headers ?? [] as $header) {
            $headers[$header->objectId] = $header;
        }

        return Arr::filter([
            'contentType' => $this->contentType,
            'headers' => [] !== $headers ? $headers : null,
            'style' => $this->style,
            'explode' => $this->explode,
            'allowReserved' => $this->allowReserved,
        ]);
    }
}
