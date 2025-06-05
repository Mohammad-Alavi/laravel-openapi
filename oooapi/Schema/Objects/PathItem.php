<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

class PathItem extends ExtensibleObject implements SimpleCreator
{
    use SimpleCreatorTrait;

    protected string|null $summary = null;
    protected string|null $description = null;

    /** @var Operation[]|null */
    protected array|null $operations = null;

    /** @var Server[]|null */
    protected array|null $servers = null;

    /** @var Parameter[]|null */
    protected array|null $parameters = null;

    public function summary(string|null $summary): static
    {
        $clone = clone $this;

        $clone->summary = $summary;

        return $clone;
    }

    public function description(string|null $description): static
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function operations(Operation ...$operation): static
    {
        $clone = clone $this;

        $clone->operations = [] !== $operation ? $operation : null;

        return $clone;
    }

    public function servers(Server ...$server): static
    {
        $clone = clone $this;

        $clone->servers = [] !== $server ? $server : null;

        return $clone;
    }

    // TODO: change this to use Parameters object instead of an array of Parameters
    public function parameters(Parameter ...$parameter): static
    {
        $clone = clone $this;

        $clone->parameters = [] !== $parameter ? $parameter : null;

        return $clone;
    }

    protected function toArray(): array
    {
        $operations = [];
        foreach ($this->operations ?? [] as $operation) {
            $operations[$operation->method] = $operation;
        }

        return Arr::filter(
            [
                'summary' => $this->summary,
                'description' => $this->description,
                ...$operations,
                'servers' => $this->servers,
                'parameters' => $this->parameters,
            ],
        );
    }
}
