<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Vocabulary;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "$vocabulary" keyword declares which vocabularies are used by a meta-schema.
 *
 * Maps vocabulary URIs to booleans indicating if support is required.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-8.1.2
 */
final readonly class Vocabulary implements Keyword
{
    /** @param Vocab[] $vocabs */
    private function __construct(
        private array $vocabs,
    ) {
    }

    public static function create(Vocab ...$vocab): self
    {
        return new self($vocab);
    }

    public static function name(): string
    {
        return '$vocabulary';
    }

    public function jsonSerialize(): array
    {
        $vocabulary = [];
        foreach ($this->value() as $vocab) {
            $vocabulary[$vocab->id()] = $vocab->required();
        }

        return $vocabulary;
    }

    /**
     * @return Vocab[]
     */
    public function value(): array
    {
        return $this->vocabs;
    }
}
