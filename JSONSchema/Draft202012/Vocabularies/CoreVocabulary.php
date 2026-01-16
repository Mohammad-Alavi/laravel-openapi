<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Anchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Comment;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs\Defs;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DynamicAnchor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DynamicRef;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Id;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Ref;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Schema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Vocabulary\Vocabulary as VocabularyKeyword;

/**
 * JSON Schema Draft 2020-12 Core Vocabulary.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-8
 */
final readonly class CoreVocabulary implements Vocabulary
{
    public function id(): string
    {
        return 'https://json-schema.org/draft/2020-12/vocab/core';
    }

    public function isRequired(): bool
    {
        return true;
    }

    public function keywords(): array
    {
        return [
            Schema::class,
            Id::class,
            Anchor::class,
            Ref::class,
            DynamicAnchor::class,
            DynamicRef::class,
            VocabularyKeyword::class,
            Comment::class,
            Defs::class,
        ];
    }
}
