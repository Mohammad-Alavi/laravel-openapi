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
    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    private OperationRef|null $operationRef = null;
    private OperationId|null $operationId = null;
    private Description|null $description = null;
    private Server|null $server = null;

    public function operationRef(OperationRef|null $operationRef): self
    {
        $clone = clone $this;

        $clone->operationRef = $operationRef;

        return $clone;
    }

    public function operationId(OperationId|null $operationId): self
    {
        $clone = clone $this;

        $clone->operationId = $operationId;

        return $clone;
    }

    public function description(Description|null $description): self
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function server(Server|null $server): self
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
