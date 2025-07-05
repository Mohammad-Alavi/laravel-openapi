<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Vocabulary;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

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
