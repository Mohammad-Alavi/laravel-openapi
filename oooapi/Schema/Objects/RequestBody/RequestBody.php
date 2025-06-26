<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\Fields\Required;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Content\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class RequestBody extends ExtensibleObject
{
    private Description|null $description = null;
    private Required|null $required = null;
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

        $clone->required = Required::yes();

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

    protected function toArray(): array
    {
        return Arr::filter([
            'description' => $this->description,
            'content' => $this->content,
            'required' => $this->required,
        ]);
    }
}
