<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Contracts\SchemaContract;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property Schema|null $schema
 */
class Not extends BaseObject implements SchemaContract
{
    /**
     * @var Schema|null
     */
    protected $schema;

    /**
     * @return static
     */
    public function schema(Schema|null $schema): self
    {
        $instance = clone $this;

        $instance->schema = $schema;

        return $instance;
    }

    protected function generate(): array
    {
        return Arr::filter([
            'not' => $this->schema,
        ]);
    }
}
