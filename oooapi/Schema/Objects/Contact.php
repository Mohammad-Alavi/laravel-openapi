<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

class Contact extends ExtensibleObject implements SimpleCreator
{
    use SimpleCreatorTrait;

    protected string|null $name = null;
    protected string|null $url = null;
    protected string|null $email = null;

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

    public function email(string|null $email): static
    {
        $clone = clone $this;

        $clone->email = $email;

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'url' => $this->url,
            'email' => $this->email,
        ]);
    }
}
