<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Constant;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentRequired\DependentRequired;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Enum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ExclusiveMaximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ExclusiveMinimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Maximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Minimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinLength;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MultipleOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Pattern;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Required;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UniqueItems;

/**
 * JSON Schema Draft 2020-12 Validation Vocabulary.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-6
 */
final readonly class ValidationVocabulary implements Vocabulary
{
    public function id(): string
    {
        return 'https://json-schema.org/draft/2020-12/vocab/validation';
    }

    public function isRequired(): bool
    {
        return true;
    }

    public function keywords(): array
    {
        return [
            Type::class,
            MultipleOf::class,
            Maximum::class,
            ExclusiveMaximum::class,
            Minimum::class,
            ExclusiveMinimum::class,
            MaxLength::class,
            MinLength::class,
            Pattern::class,
            MaxItems::class,
            MinItems::class,
            UniqueItems::class,
            MaxContains::class,
            MinContains::class,
            MaxProperties::class,
            MinProperties::class,
            Required::class,
            DependentRequired::class,
            Constant::class,
            Enum::class,
        ];
    }
}
