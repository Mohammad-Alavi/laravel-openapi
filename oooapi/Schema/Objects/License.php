<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

class License extends ExtensibleObject implements SimpleCreator
{
    use SimpleCreatorTrait;

    protected string|null $name = null;
    protected string|null $url = null;

    public function name(string|null $name): static
    {
        $clone = clone $this;

        $clone->name = $name;

        return $clone;
    }

    public function url(string|null $url): static
    {
        $clone = clone $this;

        $clone->url = $url;

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'url' => $this->url,
        ]);
    }
}
