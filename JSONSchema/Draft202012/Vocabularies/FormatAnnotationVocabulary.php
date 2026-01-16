<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Format;

/**
 * JSON Schema Draft 2020-12 Format-Annotation Vocabulary.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-7
 */
final readonly class FormatAnnotationVocabulary implements Vocabulary
{
    public function id(): string
    {
        return 'https://json-schema.org/draft/2020-12/vocab/format-annotation';
    }

    public function isRequired(): bool
    {
        return false;
    }

    public function keywords(): array
    {
        return [
            Format::class,
        ];
    }
}
