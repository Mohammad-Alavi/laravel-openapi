<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property string|null $contentType
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Header[]|null $headers
 * @property string|null $style
 * @property bool|null $explode
 * @property bool|null $allowReserved
 */
class Encoding extends BaseObject
{
    /**
     * @var string|null
     */
    protected $contentType;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Header[]|null
     */
    protected $headers;

    /**
     * @var string|null
     */
    protected $style;

    /**
     * @var bool|null
     */
    protected $explode;

    /**
     * @var bool|null
     */
    protected $allowReserved;

    /**
     * @return static
     */
    public function contentType(string|null $contentType): self
    {
        $instance = clone $this;

        $instance->contentType = $contentType;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Header[] $headers
     *
     * @return static
     */
    public function headers(Header ...$headers): self
    {
        $instance = clone $this;

        $instance->headers = $headers ?: null;

        return $instance;
    }

    /**
     * @return static
     */
    public function style(string|null $style): self
    {
        $instance = clone $this;

        $instance->style = $style;

        return $instance;
    }

    /**
     * @return static
     */
    public function explode(bool|null $explode = true): self
    {
        $instance = clone $this;

        $instance->explode = $explode;

        return $instance;
    }

    /**
     * @return static
     */
    public function allowReserved(bool|null $allowReserved = true): self
    {
        $instance = clone $this;

        $instance->allowReserved = $allowReserved;

        return $instance;
    }

    protected function generate(): array
    {
        $headers = [];
        foreach ($this->headers ?? [] as $header) {
            $headers[$header->objectId] = $header;
        }

        return Arr::filter([
            'contentType' => $this->contentType,
            'headers' => $headers ?: null,
            'style' => $this->style,
            'explode' => $this->explode,
            'allowReserved' => $this->allowReserved,
        ]);
    }
}
