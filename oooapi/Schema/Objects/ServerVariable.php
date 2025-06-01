<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleKeyCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleKeyCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

class ServerVariable extends ExtensibleObject implements SimpleKeyCreator
{
    use SimpleKeyCreatorTrait;

    /** @var string[]|null */
    protected array|null $enum = null;

    protected string|null $default = null;
    protected string|null $description = null;

    public function enum(string ...$enum): static
    {
        $clone = clone $this;

        $clone->enum = [] !== $enum ? $enum : null;

        return $clone;
    }

    public function default(string|null $default): static
    {
        $clone = clone $this;

        $clone->default = $default;

        return $clone;
    }

    public function description(string|null $description): static
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'enum' => $this->enum,
            'default' => $this->default,
            'description' => $this->description,
        ]);
    }
}
