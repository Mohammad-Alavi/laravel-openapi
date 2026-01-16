<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UnevaluatedItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UnevaluatedProperties;

/**
 * JSON Schema Draft 2020-12 Unevaluated Vocabulary.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-11
 */
final readonly class UnevaluatedVocabulary implements Vocabulary
{
    public function id(): string
    {
        return 'https://json-schema.org/draft/2020-12/vocab/unevaluated';
    }

    public function isRequired(): bool
    {
        return false;
    }

    public function keywords(): array
    {
        return [
            UnevaluatedItems::class,
            UnevaluatedProperties::class,
        ];
    }
}
