<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Validation;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\KeywordRegistry;

/**
 * Validates that a schema only uses keywords from declared vocabularies.
 *
 * This is "vocabulary validation" - checking that a schema conforms to
 * its declared vocabulary requirements. This is different from "data validation"
 * (checking that data conforms to a schema).
 *
 * Use cases:
 * - Validate schemas before deployment
 * - Ensure schemas only use supported keywords
 * - Check compatibility with specific validators
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-4.3.3
 */
final class VocabularyValidator
{
    /** @var array<string, bool> Vocabulary ID => required */
    private array $vocabularyRequirements = [];

    private KeywordRegistry $registry;

    public function __construct(KeywordRegistry $registry)
    {
        $this->registry = $registry;

        // Initialize requirements from registry
        foreach ($registry->getVocabularies() as $vocab) {
            $this->vocabularyRequirements[$vocab->id()] = $vocab->isRequired();
        }
    }

    /**
     * Create a validator for a specific dialect.
     *
     * @param array<string, bool> $vocabularyDeclaration The $vocabulary object from meta-schema
     */
    public static function fromVocabularyDeclaration(
        array $vocabularyDeclaration,
        KeywordRegistry $registry,
    ): self {
        $validator = new self($registry);
        $validator->vocabularyRequirements = $vocabularyDeclaration;

        return $validator;
    }

    /**
     * Validate a compiled schema array.
     *
     * @param array<string, mixed> $schema The compiled schema
     */
    public function validate(array $schema): VocabularyValidationResult
    {
        $errors = [];
        $warnings = [];
        $unknownKeywords = [];
        $usedVocabularies = [];

        foreach ($schema as $keyword => $value) {
            // Skip special properties
            if (in_array($keyword, ['$schema', '$vocabulary'], true)) {
                continue;
            }

            if ($this->registry->hasKeyword($keyword)) {
                $keywordClass = $this->registry->getKeywordClass($keyword);
                $vocab = $this->registry->getVocabularyForKeyword($keywordClass);

                if (null !== $vocab) {
                    $usedVocabularies[$vocab->id()] = true;
                }
            } else {
                $unknownKeywords[] = $keyword;
            }

            // Recursively validate nested schemas
            if (is_array($value)) {
                $this->validateNested($value, $errors, $warnings, $unknownKeywords, $usedVocabularies);
            }
        }

        // Check for unknown keywords
        foreach ($unknownKeywords as $keyword) {
            $errors[] = new VocabularyValidationError(
                "Unknown keyword '{$keyword}' is not part of any registered vocabulary",
                $keyword,
                VocabularyValidationError::UNKNOWN_KEYWORD,
            );
        }

        // Check required vocabularies are available
        foreach ($this->vocabularyRequirements as $vocabId => $required) {
            if ($required && !$this->registry->hasVocabulary($vocabId)) {
                $errors[] = new VocabularyValidationError(
                    "Required vocabulary '{$vocabId}' is not registered",
                    $vocabId,
                    VocabularyValidationError::MISSING_REQUIRED_VOCABULARY,
                );
            }
        }

        return new VocabularyValidationResult(
            empty($errors),
            $errors,
            $warnings,
            array_keys($usedVocabularies),
            $unknownKeywords,
        );
    }

    /**
     * Check if this validator supports all required vocabularies.
     */
    public function supportsRequiredVocabularies(): bool
    {
        foreach ($this->vocabularyRequirements as $vocabId => $required) {
            if ($required && !$this->registry->hasVocabulary($vocabId)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get missing required vocabularies.
     *
     * @return string[]
     */
    public function getMissingRequiredVocabularies(): array
    {
        $missing = [];

        foreach ($this->vocabularyRequirements as $vocabId => $required) {
            if ($required && !$this->registry->hasVocabulary($vocabId)) {
                $missing[] = $vocabId;
            }
        }

        return $missing;
    }

    /**
     * @param array<string, mixed> $value
     * @param VocabularyValidationError[] $errors
     * @param VocabularyValidationError[] $warnings
     * @param string[] $unknownKeywords
     * @param array<string, bool> $usedVocabularies
     */
    private function validateNested(
        array $value,
        array &$errors,
        array &$warnings,
        array &$unknownKeywords,
        array &$usedVocabularies,
    ): void {
        foreach ($value as $key => $nested) {
            // Check if this looks like a schema keyword
            if (is_string($key) && $this->registry->hasKeyword($key)) {
                $keywordClass = $this->registry->getKeywordClass($key);
                $vocab = $this->registry->getVocabularyForKeyword($keywordClass);

                if (null !== $vocab) {
                    $usedVocabularies[$vocab->id()] = true;
                }
            }

            // Recurse into nested arrays
            if (is_array($nested)) {
                $this->validateNested($nested, $errors, $warnings, $unknownKeywords, $usedVocabularies);
            }
        }
    }
}
