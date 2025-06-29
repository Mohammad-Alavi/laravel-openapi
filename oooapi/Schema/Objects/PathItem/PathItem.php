<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\Operations;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Summary;

final class PathItem extends ExtensibleObject
{
    private Summary|null $summary = null;
    private Description|null $description = null;
    private Operations|null $operations = null;

    /** @var Server[]|null */
    private array|null $servers = null;

    /** @var Parameter[]|null */
    private array|null $parameters = null;

    public function summary(string $summary): self
    {
        $clone = clone $this;

        $clone->summary = Summary::create($summary);

        return $clone;
    }

    public static function create(): self
    {
        return new self();
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public function operations(AvailableOperation ...$availableOperation): self
    {
        $clone = clone $this;

        $clone->operations = Operations::create(...$availableOperation);

        return $clone;
    }

    public function servers(Server ...$server): self
    {
        $clone = clone $this;

        $clone->servers = when(blank($server), null, $server);

        return $clone;
    }

    // TODO: change this to use Parameters object instead of an array of Parameters
    public function parameters(Parameter ...$parameter): self
    {
        $clone = clone $this;

        $clone->parameters = when(blank($parameter), null, $parameter);

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter(
            [
                'summary' => $this->summary,
                'description' => $this->description,
                ...($this->operations?->jsonSerialize() ?? []),
                'servers' => $this->servers,
                'parameters' => $this->parameters,
            ],
        );
    }
}
