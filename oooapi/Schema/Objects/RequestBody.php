<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Collections\Content\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Collections\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

class RequestBody extends ExtensibleObject implements SimpleCreator
{
    use SimpleCreatorTrait;

    protected string|null $description = null;
    protected bool|null $required = null;
    private Content|null $content = null;

    public function description(string|null $description): static
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function content(ContentEntry ...$contentEntry): self
    {
        $clone = clone $this;

        $clone->content = Content::create(...$contentEntry);

        return $clone;
    }

    public function required(bool|null $required = true): static
    {
        $clone = clone $this;

        $clone->required = $required;

        return $clone;
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
