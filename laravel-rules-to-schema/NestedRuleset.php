<?php

namespace MohammadAlavi\LaravelRulesToSchema;

final readonly class NestedRuleset
{
    /**
     * @param list<ValidationRule> $validationRules
     * @param array<string, self> $children
     */
    public function __construct(
        public array $validationRules = [],
        public array $children = [],
    ) {
    }

    public function hasChildren(): bool
    {
        return [] !== $this->children;
    }

    public function hasWildcardChild(): bool
    {
        return array_key_exists('*', $this->children);
    }

    public function wildcardChild(): self|null
    {
        return $this->children['*'] ?? null;
    }
}
