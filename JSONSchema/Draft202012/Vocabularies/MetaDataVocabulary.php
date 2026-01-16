<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DefaultValue;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Deprecated;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Description;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Examples;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\IsReadOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\IsWriteOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Title;

/**
 * JSON Schema Draft 2020-12 Meta-Data Vocabulary.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-9
 */
final readonly class MetaDataVocabulary implements Vocabulary
{
    public function id(): string
    {
        return 'https://json-schema.org/draft/2020-12/vocab/meta-data';
    }

    public function isRequired(): bool
    {
        return false;
    }

    public function keywords(): array
    {
        return [
            Title::class,
            Description::class,
            DefaultValue::class,
            Deprecated::class,
            IsReadOnly::class,
            IsWriteOnly::class,
            Examples::class,
        ];
    }
}
