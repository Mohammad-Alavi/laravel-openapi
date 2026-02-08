<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping\Entry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping\Mapping;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\PropertyName;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

/**
 * Discriminator Object.
 *
 * Used with composition keywords (oneOf, anyOf, allOf) to help determine
 * which schema to use based on a property value. The propertyName field
 * is required.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#discriminator-object
 */
final class Discriminator extends ExtensibleObject
{
    private string|null $defaultMapping = null;

    private function __construct(
        private readonly PropertyName $propertyName,
        private readonly Mapping|null $mapping = null,
    ) {
    }

    public static function create(
        string $propertyName,
        Entry ...$entry,
    ): self {
        return new self(
            PropertyName::create($propertyName),
            when(blank($entry), null, Mapping::create(...$entry)),
        );
    }

    public function defaultMapping(string $schemaNameOrUri): self
    {
        $clone = clone $this;

        $clone->defaultMapping = $schemaNameOrUri;

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'propertyName' => $this->propertyName,
            'mapping' => $this->mapping,
            'defaultMapping' => $this->defaultMapping,
        ]);
    }
}
