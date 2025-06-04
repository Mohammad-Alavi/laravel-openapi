<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleKeyCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleKeyCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

class Link extends ExtensibleObject implements SimpleKeyCreator
{
    use SimpleKeyCreatorTrait;

    protected string|null $operationRef = null;
    protected string|null $operationId = null;
    protected string|null $description = null;
    protected Server|null $server = null;

    public function operationRef(string|null $operationRef): static
    {
        $clone = clone $this;

        $clone->operationRef = $operationRef;

        return $clone;
    }

    public function operationId(string|null $operationId): static
    {
        $clone = clone $this;

        $clone->operationId = $operationId;

        return $clone;
    }

    public function description(string|null $description): static
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function server(Server|null $server): static
    {
        $clone = clone $this;

        $clone->server = $server;

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'operationRef' => $this->operationRef,
            'operationId' => $this->operationId,
            'description' => $this->description,
            'server' => $this->server,
        ]);
    }
}
