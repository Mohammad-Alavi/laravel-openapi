<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Vocabulary;

/**
 * Registry for managing keywords across vocabularies.
 *
 * Use cases:
 * - Validate that a keyword belongs to a registered vocabulary
 * - Get all keywords for a dialect
 * - Check if a keyword is supported
 * - Get vocabulary information for a keyword
 */
final class KeywordRegistry
{
    /** @var array<string, Vocabulary> */
    private array $vocabularies = [];

    /** @var array<string, class-string<Keyword>> */
    private array $keywords = [];

    /** @var array<class-string<Keyword>, Vocabulary> */
    private array $keywordToVocabulary = [];

    /**
     * Register a vocabulary and all its keywords.
     */
    public function registerVocabulary(Vocabulary $vocabulary): self
    {
        $this->vocabularies[$vocabulary->id()] = $vocabulary;

        foreach ($vocabulary->keywords() as $keywordClass) {
            $keywordName = $keywordClass::name();
            $this->keywords[$keywordName] = $keywordClass;
            $this->keywordToVocabulary[$keywordClass] = $vocabulary;
        }

        return $this;
    }

    /**
     * Register multiple vocabularies.
     */
    public function registerVocabularies(Vocabulary ...$vocabularies): self
    {
        foreach ($vocabularies as $vocabulary) {
            $this->registerVocabulary($vocabulary);
        }

        return $this;
    }

    /**
     * Check if a keyword name is registered.
     */
    public function hasKeyword(string $name): bool
    {
        return isset($this->keywords[$name]);
    }

    /**
     * Get keyword class by name.
     *
     * @return class-string<Keyword>|null
     */
    public function getKeywordClass(string $name): string|null
    {
        return $this->keywords[$name] ?? null;
    }

    /**
     * Get vocabulary for a keyword class.
     */
    public function getVocabularyForKeyword(string $keywordClass): Vocabulary|null
    {
        return $this->keywordToVocabulary[$keywordClass] ?? null;
    }

    /**
     * Get all registered vocabulary IDs.
     *
     * @return string[]
     */
    public function getVocabularyIds(): array
    {
        return array_keys($this->vocabularies);
    }

    /**
     * Get a vocabulary by ID.
     */
    public function getVocabulary(string $id): Vocabulary|null
    {
        return $this->vocabularies[$id] ?? null;
    }

    /**
     * Get all registered keyword names.
     *
     * @return string[]
     */
    public function getKeywordNames(): array
    {
        return array_keys($this->keywords);
    }

    /**
     * Get all required vocabularies.
     *
     * @return Vocabulary[]
     */
    public function getRequiredVocabularies(): array
    {
        return array_filter(
            $this->vocabularies,
            static fn (Vocabulary $v): bool => $v->isRequired(),
        );
    }

    /**
     * Get all optional vocabularies.
     *
     * @return Vocabulary[]
     */
    public function getOptionalVocabularies(): array
    {
        return array_filter(
            $this->vocabularies,
            static fn (Vocabulary $v): bool => !$v->isRequired(),
        );
    }

    /**
     * Check if a vocabulary is registered.
     */
    public function hasVocabulary(string $id): bool
    {
        return isset($this->vocabularies[$id]);
    }

    /**
     * Get all registered vocabularies.
     *
     * @return Vocabulary[]
     */
    public function getVocabularies(): array
    {
        return array_values($this->vocabularies);
    }
}
