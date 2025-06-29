<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;

final class RequestBody extends ExtensibleObject
{
    private Description|null $description = null;
    private true|null $required = null;
    private Content|null $content = null;

    public function description(Description|null $description): self
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function required(): self
    {
        $clone = clone $this;

        $clone->required = true;

        return $clone;
    }

    public function content(ContentEntry ...$contentEntry): self
    {
        $clone = clone $this;

        $clone->content = Content::create(...$contentEntry);

        return $clone;
    }

    public static function create(): self
    {
        return new self();
    }

    public function toArray(): array
    {
        return Arr::filter([
            'description' => $this->description,
            'content' => $this->content,
            'required' => $this->required,
        ]);
    }
}
