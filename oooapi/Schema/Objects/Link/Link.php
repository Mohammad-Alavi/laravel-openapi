<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Fields\OperationId;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Fields\OperationRef;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;

final class Link extends ExtensibleObject
{
    private OperationRef|null $operationRef = null;
    private OperationId|null $operationId = null;
    private Description|null $description = null;
    private Server|null $server = null;

    public function operationRef(string $operationRef): self
    {
        $clone = clone $this;

        $clone->operationRef = OperationRef::create($operationRef);

        return $clone;
    }

    public static function create(): self
    {
        return new self();
    }

    public function operationId(string $operationId): self
    {
        $clone = clone $this;

        $clone->operationId = OperationId::create($operationId);

        return $clone;
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public function server(Server $server): self
    {
        $clone = clone $this;

        $clone->server = $server;

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'operationRef' => $this->operationRef,
            'operationId' => $this->operationId,
            'description' => $this->description,
            'server' => $this->server,
        ]);
    }
}
