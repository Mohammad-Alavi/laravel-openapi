<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping\Mapping;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\PropertyName;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class Discriminator extends ExtensibleObject
{
    private function __construct(
        private readonly PropertyName $propertyName,
        private readonly Mapping|null $mapping = null,
    ) {
    }

    public static function create(
        PropertyName $propertyName,
        Mapping|null $mapping = null,
    ): self {
        return new self($propertyName, $mapping);
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'propertyName' => $this->propertyName,
            'mapping' => $this->mapping,
        ]);
    }
}
