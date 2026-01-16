<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Extensions\OpenAPI\Concerns;

use MohammadAlavi\ObjectOrientedJSONSchema\Extensions\OpenAPI\Keywords\Discriminator;
use MohammadAlavi\ObjectOrientedJSONSchema\Extensions\OpenAPI\Keywords\ExternalDocs;

/**
 * Trait providing OpenAPI extension keywords with full autocomplete.
 *
 * Usage:
 * ```php
 * class MyDescriptor extends StrictFluentDescriptor
 * {
 *     use HasOpenAPIKeywords;
 * }
 *
 * MyDescriptor::object()
 *     ->properties(...)
 *     ->discriminator('type', ['dog' => '#/components/schemas/Dog'])
 *     ->externalDocs('https://example.com/docs');
 * ```
 */
trait HasOpenAPIKeywords
{
    /**
     * Add a discriminator for polymorphic schemas.
     *
     * @param string $propertyName The property that determines the type
     * @param array<string, string> $mapping Maps values to schema references
     *
     * @see https://spec.openapis.org/oas/v3.1.0#discriminator-object
     */
    public function discriminator(string $propertyName, array $mapping = []): static
    {
        return $this->set(Discriminator::create($propertyName, $mapping));
    }

    /**
     * Add external documentation reference.
     *
     * @param string $url URL to external documentation
     * @param string|null $description Description of the external docs
     *
     * @see https://spec.openapis.org/oas/v3.1.0#external-documentation-object
     */
    public function externalDocs(string $url, string|null $description = null): static
    {
        return $this->set(ExternalDocs::create($url, $description));
    }
}
