<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property string|null $propertyName
 * @property array|null $mapping
 */
class Discriminator extends BaseObject
{
    /**
     * @var string|null
     */
    protected $propertyName;

    /**
     * @var array|null
     */
    protected $mapping;

    /**
     * @return static
     */
    public function propertyName(string|null $propertyName): self
    {
        $instance = clone $this;

        $instance->propertyName = $propertyName;

        return $instance;
    }

    /**
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public function mapping(array $mapping): self
    {
        // Ensure the mappings are string => string.
        foreach ($mapping as $key => $value) {
            if (is_string($key) && is_string($value)) {
                continue;
            }

            throw new InvalidArgumentException('Each mapping must have a string key and a string value.');
        }

        $instance = clone $this;

        $instance->mapping = $mapping ?: null;

        return $instance;
    }

    protected function generate(): array
    {
        return Arr::filter([
            'propertyName' => $this->propertyName,
            'mapping' => $this->mapping,
        ]);
    }
}
