<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property string|null $description
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\MediaType[]|null $content
 * @property bool|null $required
 */
class RequestBody extends BaseObject
{
    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\MediaType[]|null
     */
    protected $content;

    /**
     * @var bool|null
     */
    protected $required;

    /**
     * @return static
     */
    public function description(string|null $description): self
    {
        $instance = clone $this;

        $instance->description = $description;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\MediaType[] $content
     *
     * @return static
     */
    public function content(MediaType ...$content): self
    {
        $instance = clone $this;

        $instance->content = $content ?: null;

        return $instance;
    }

    /**
     * @return static
     */
    public function required(bool|null $required = true): self
    {
        $instance = clone $this;

        $instance->required = $required;

        return $instance;
    }

    protected function generate(): array
    {
        $content = [];
        foreach ($this->content ?? [] as $contentItem) {
            $content[$contentItem->mediaType] = $contentItem;
        }

        return Arr::filter([
            'description' => $this->description,
            'content' => $content ?: null,
            'required' => $this->required,
        ]);
    }
}
