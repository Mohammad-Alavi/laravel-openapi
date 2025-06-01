<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\MetaSchema;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

final readonly class MetaSchema implements MetaSchemaInterface
{
    private array $availableVocabularies;
    private array $availableKeywords;

    public function __construct(
        private string $id,
        private string $schema,
        AvailableVocabulary ...$availableVocabulary,
    ) {
        $availableKeywords = $this->getAvailableKeywords(...$availableVocabulary);
        $this->validateKeywordUniqueness(...$availableKeywords);

        $this->availableKeywords = $availableKeywords;
        $this->availableVocabularies = $availableVocabulary;
    }

    /** @return  array<array-key, Keyword> */
    private function getAvailableKeywords(AvailableVocabulary ...$availableVocabulary): array
    {
        return collect(
            $availableVocabulary,
        )->flatMap(
            static fn (
                AvailableVocabulary $availableVocabulary,
            ): array => $availableVocabulary->vocabulary()->keywords(),
        )->toArray();
    }

    private function validateKeywordUniqueness(Keyword ...$keyword): void
    {
        $duplicates = collect($keyword)->duplicates(
            static fn (Keyword $keyword): string => $keyword->name(),
        );

        if ($duplicates->isNotEmpty()) {
            // TODO: Improve error message by including the vocabulary id for each duplicate keyword.
            throw new \DomainException('Duplicate keywords found: ' . $duplicates->implode(', '));
        }
    }

    public function availableKeywords(): array
    {
        return $this->availableKeywords;
    }

    public function availableVocabularies(): array
    {
        return $this->availableVocabularies;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function schema(): string
    {
        return $this->schema;
    }
}
