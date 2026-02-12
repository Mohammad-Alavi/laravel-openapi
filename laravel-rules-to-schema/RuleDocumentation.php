<?php

namespace MohammadAlavi\LaravelRulesToSchema;

final readonly class RuleDocumentation
{
    /** @param list<string> $enum */
    public function __construct(
        public string|null $type = null,
        public string|null $format = null,
        public string|null $description = null,
        public array $enum = [],
    ) {
    }

    public function hasType(): bool
    {
        return null !== $this->type;
    }

    public function hasFormat(): bool
    {
        return null !== $this->format;
    }

    public function hasDescription(): bool
    {
        return null !== $this->description;
    }

    public function hasEnum(): bool
    {
        return [] !== $this->enum;
    }
}
