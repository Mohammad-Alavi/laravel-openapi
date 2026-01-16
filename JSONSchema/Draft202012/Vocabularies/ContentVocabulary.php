<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ContentEncoding;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ContentMediaType;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ContentSchema;

/**
 * JSON Schema Draft 2020-12 Content Vocabulary.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-8
 */
final readonly class ContentVocabulary implements Vocabulary
{
    public function id(): string
    {
        return 'https://json-schema.org/draft/2020-12/vocab/content';
    }

    public function isRequired(): bool
    {
        return false;
    }

    public function keywords(): array
    {
        return [
            ContentEncoding::class,
            ContentMediaType::class,
            ContentSchema::class,
        ];
    }
}
