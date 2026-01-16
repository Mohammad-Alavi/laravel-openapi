<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Contracts;

/**
 * A vocabulary groups related keywords.
 *
 * Use cases:
 * - Organization: Group keywords by JSON Schema vocabulary
 * - Validation: Check if a keyword belongs to a vocabulary
 * - Documentation: List all keywords in a vocabulary
 *
 * NOT for autocomplete - that requires explicit methods on descriptors.
 *
 * @see https://json-schema.org/understanding-json-schema/reference/schema#vocabulary
 */
interface Vocabulary
{
    /**
     * Vocabulary identifier URI.
     */
    public function id(): string;

    /**
     * Whether implementations MUST support all keywords in this vocabulary.
     */
    public function isRequired(): bool;

    /**
     * Keyword classes in this vocabulary.
     *
     * @return array<class-string<Keyword>>
     */
    public function keywords(): array;
}
