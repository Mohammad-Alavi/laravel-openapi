<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Contracts\SchemaContract;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Schema[]|null $schemas
 */
abstract class SchemaComposition extends BaseObject implements SchemaContract
{
    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Schema[]|null
     */
    protected $schemas;

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Schema[] $schemas
     *
     * @return static
     */
    public function schemas(Schema ...$schemas): self
    {
        $instance = clone $this;

        $instance->schemas = $schemas ?: null;

        return $instance;
    }

    abstract protected function compositionType(): string;

    protected function generate(): array
    {
        return Arr::filter([
            $this->compositionType() => $this->schemas,
        ]);
    }
}
