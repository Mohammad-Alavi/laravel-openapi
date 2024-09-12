<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property string|null $name
 * @property string|null $description
 * @property ExternalDocs|null $externalDocs
 */
class Tag extends BaseObject
{
    protected string|null $name = null;
    protected string|null $description = null;
    protected ExternalDocs|null $externalDocs = null;

    /** @return static */
    public function name(string|null $name): self
    {
        $instance = clone $this;

        $instance->name = $name;

        return $instance;
    }

    /** @return static */
    public function description(string|null $description): self
    {
        $instance = clone $this;

        $instance->description = $description;

        return $instance;
    }

    /** @return static */
    public function externalDocs(ExternalDocs|null $externalDocs): self
    {
        $instance = clone $this;

        $instance->externalDocs = $externalDocs;

        return $instance;
    }

    protected function generate(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'description' => $this->description,
            'externalDocs' => $this->externalDocs,
        ]);
    }
}
