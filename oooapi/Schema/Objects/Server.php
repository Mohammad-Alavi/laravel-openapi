<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

class Server extends ExtensibleObject implements SimpleCreator
{
    use SimpleCreatorTrait;

    protected string|null $url = null;
    protected string|null $description = null;

    /** @var ServerVariable[]|null */
    protected array|null $variables = null;

    public function url(string|null $url): static
    {
        $clone = clone $this;

        $clone->url = $url;

        return $clone;
    }

    public function description(string|null $description): static
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function variables(ServerVariable ...$serverVariable): static
    {
        $clone = clone $this;

        $clone->variables = [] !== $serverVariable ? $serverVariable : null;

        return $clone;
    }

    protected function toArray(): array
    {
        $variables = [];
        foreach ($this->variables ?? [] as $variable) {
            $variables[$variable->key()] = $variable;
        }

        return Arr::filter([
            'url' => $this->url,
            'description' => $this->description,
            'variables' => [] !== $variables ? $variables : null,
        ]);
    }
}
