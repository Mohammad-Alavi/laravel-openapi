<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\Operations;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Summary;

/**
 * Path Item Object.
 *
 * Describes the operations available on a single path. A Path Item MAY be
 * empty due to ACL constraints. The path itself is still exposed to the
 * documentation viewer but they will not know which operations are available.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#path-item-object
 */
final class PathItem extends ExtensibleObject
{
    private Summary|null $summary = null;
    private Description|null $description = null;
    private Operations|null $operations = null;

    /** @var Server[]|null */
    private array|null $servers = null;

    private Parameters|null $parameters = null;

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

    public function getOperations(): Operations|null
    {
        return $this->operations;
    }

    public function servers(Server ...$server): self
    {
        $clone = clone $this;

        $clone->servers = when(blank($server), null, $server);

        return $clone;
    }

    public function parameters(Parameters $parameters): self
    {
        $clone = clone $this;

        $clone->parameters = $parameters->toNullIfEmpty();

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter(
            [
                'summary' => $this->summary,
                'description' => $this->description,
                ...$this->mergeFields($this->operations),
                'servers' => $this->servers,
                'parameters' => $this->parameters,
            ],
        );
    }
}
