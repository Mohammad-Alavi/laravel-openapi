<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\ApplicatorVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\ContentVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\CoreVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\FormatAnnotationVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\MetaDataVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\UnevaluatedVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\ValidationVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\KeywordRegistry;

/**
 * JSON Schema Draft 2020-12 Dialect.
 *
 * A dialect defines which vocabularies are available and composes them
 * into a complete JSON Schema implementation.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-4.3.3
 */
final class Draft202012Dialect
{
    public const SCHEMA_URI = 'https://json-schema.org/draft/2020-12/schema';

    private KeywordRegistry $registry;

    /** @var Vocabulary[] */
    private array $vocabularies;

    public function __construct()
    {
        $this->vocabularies = [
            new CoreVocabulary(),
            new ApplicatorVocabulary(),
            new UnevaluatedVocabulary(),
            new ValidationVocabulary(),
            new MetaDataVocabulary(),
            new FormatAnnotationVocabulary(),
            new ContentVocabulary(),
        ];

        $this->registry = new KeywordRegistry();
        $this->registry->registerVocabularies(...$this->vocabularies);
    }

    /**
     * Get the schema URI for this dialect.
     */
    public function schemaUri(): string
    {
        return self::SCHEMA_URI;
    }

    /**
     * Get the keyword registry for this dialect.
     */
    public function registry(): KeywordRegistry
    {
        return $this->registry;
    }

    /**
     * Get all vocabularies in this dialect.
     *
     * @return Vocabulary[]
     */
    public function vocabularies(): array
    {
        return $this->vocabularies;
    }

    /**
     * Get required vocabularies.
     *
     * @return Vocabulary[]
     */
    public function requiredVocabularies(): array
    {
        return $this->registry->getRequiredVocabularies();
    }

    /**
     * Get optional vocabularies.
     *
     * @return Vocabulary[]
     */
    public function optionalVocabularies(): array
    {
        return $this->registry->getOptionalVocabularies();
    }

    /**
     * Check if a keyword name is supported by this dialect.
     */
    public function supportsKeyword(string $name): bool
    {
        return $this->registry->hasKeyword($name);
    }

    /**
     * Get all supported keyword names.
     *
     * @return string[]
     */
    public function supportedKeywords(): array
    {
        return $this->registry->getKeywordNames();
    }

    /**
     * Get the $vocabulary object for use in meta-schemas.
     *
     * @return array<string, bool>
     */
    public function vocabularyDeclaration(): array
    {
        $declaration = [];
        foreach ($this->vocabularies as $vocabulary) {
            $declaration[$vocabulary->id()] = $vocabulary->isRequired();
        }

        return $declaration;
    }
}
