<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Exceptions\InvalidArgumentException;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

class Discriminator extends ExtensibleObject implements SimpleCreator
{
    use SimpleCreatorTrait;

    protected string|null $propertyName = null;
    protected array|null $mapping = null;

    public function propertyName(string|null $propertyName): static
    {
        $clone = clone $this;

        $clone->propertyName = $propertyName;

        return $clone;
    }

    /**
     * @param array<string, string> $mapping
     *
     * @throws InvalidArgumentException
     */
    public function mapping(array $mapping): static
    {
        foreach ($mapping as $key => $value) {
            if (!is_string($key) || !is_string($value)) {
                throw new InvalidArgumentException('Each mapping must have a string key and a string value.');
            }
        }

        $clone = clone $this;

        $clone->mapping = [] !== $mapping ? $mapping : null;

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'propertyName' => $this->propertyName,
            'mapping' => $this->mapping,
        ]);
    }
}
