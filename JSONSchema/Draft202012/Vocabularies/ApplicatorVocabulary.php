<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AdditionalProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AllOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AnyOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Contains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentSchemas\DependentSchemas;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ElseKeyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\IfKeyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Items;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Not;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\OneOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\PatternProperties\PatternProperties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\PrefixItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Properties;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\PropertyNames;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Then;

/**
 * JSON Schema Draft 2020-12 Applicator Vocabulary.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-10
 */
final readonly class ApplicatorVocabulary implements Vocabulary
{
    public function id(): string
    {
        return 'https://json-schema.org/draft/2020-12/vocab/applicator';
    }

    public function isRequired(): bool
    {
        return true;
    }

    public function keywords(): array
    {
        return [
            AllOf::class,
            AnyOf::class,
            OneOf::class,
            Not::class,
            IfKeyword::class,
            Then::class,
            ElseKeyword::class,
            PrefixItems::class,
            Items::class,
            Contains::class,
            Properties::class,
            PatternProperties::class,
            AdditionalProperties::class,
            PropertyNames::class,
            DependentSchemas::class,
        ];
    }
}
