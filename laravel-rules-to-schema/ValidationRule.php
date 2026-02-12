<?php

namespace MohammadAlavi\LaravelRulesToSchema;

final readonly class ValidationRule
{
    /** @param list<string> $args */
    public function __construct(
        public string|object $rule,
        public array $args = [],
    ) {
    }

    public function name(): string
    {
        return is_object($this->rule) ? get_class($this->rule) : $this->rule;
    }

    public function isString(): bool
    {
        return is_string($this->rule);
    }

    public function isObject(): bool
    {
        return is_object($this->rule);
    }

    public function arg(int $index, mixed $default = null): mixed
    {
        return $this->args[$index] ?? $default;
    }

    public function hasArgs(): bool
    {
        return [] !== $this->args;
    }
}
