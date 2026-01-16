<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Extensions\OpenAPI;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Extensions\OpenAPI\Keywords\Discriminator;
use MohammadAlavi\ObjectOrientedJSONSchema\Extensions\OpenAPI\Keywords\ExternalDocs;

/**
 * OpenAPI 3.1 Schema Object extensions vocabulary.
 *
 * This vocabulary groups OpenAPI-specific schema keywords.
 * For autocomplete, use the HasOpenAPIKeywords trait.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#schema-object
 */
final readonly class OpenAPIVocabulary implements Vocabulary
{
    public function id(): string
    {
        return 'https://spec.openapis.org/oas/3.1/vocab/base';
    }

    public function isRequired(): bool
    {
        return false;
    }

    public function keywords(): array
    {
        return [
            Discriminator::class,
            ExternalDocs::class,
        ];
    }
}
