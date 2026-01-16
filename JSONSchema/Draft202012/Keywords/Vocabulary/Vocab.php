<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Vocabulary;

/**
 * Represents a single vocabulary entry for the "$vocabulary" keyword.
 *
 * Pairs a vocabulary URI with a boolean indicating if support is required.
 *
 * @see Vocabulary The parent keyword class
 */
final readonly class Vocab
{
    private function __construct(
        private string $id,
        private bool $required,
    ) {
    }

    public static function create(string $id, bool $required): self
    {
        return new self($id, $required);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function required(): bool
    {
        return $this->required;
    }
}
