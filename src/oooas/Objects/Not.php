<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Contracts\SchemaContract;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Schema|null $schema
 */
class Not extends BaseObject implements SchemaContract
{
    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Schema|null
     */
    protected $schema;

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Schema|null $schema
     * @return static
     */
    public function schema(?Schema $schema): self
    {
        $instance = clone $this;

        $instance->schema = $schema;

        return $instance;
    }

    /**
     * @return array
     */
    protected function generate(): array
    {
        return Arr::filter([
            'not' => $this->schema,
        ]);
    }
}
