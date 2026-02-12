<?php

namespace MohammadAlavi\LaravelRulesToSchema;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class ParseResult
{
    /**
     * @param array<string, LooseFluentDescriptor>|null $expandedSchemas
     */
    private function __construct(
        private LooseFluentDescriptor|null $singleSchema,
        private array|null $expandedSchemas,
        private bool $excluded,
    ) {
    }

    public static function single(LooseFluentDescriptor $schema): self
    {
        return new self($schema, null, false);
    }

    /** @param array<string, LooseFluentDescriptor> $schemas */
    public static function expanded(array $schemas): self
    {
        return new self(null, $schemas, false);
    }

    public static function excluded(): self
    {
        return new self(null, null, true);
    }

    public function isSchema(): bool
    {
        return null !== $this->singleSchema;
    }

    public function isExpanded(): bool
    {
        return null !== $this->expandedSchemas;
    }

    public function isExcluded(): bool
    {
        return $this->excluded;
    }

    public function schema(): LooseFluentDescriptor
    {
        if (null === $this->singleSchema) {
            throw new \LogicException('Cannot access schema() on a non-schema ParseResult.');
        }

        return $this->singleSchema;
    }

    /** @return array<string, LooseFluentDescriptor> */
    public function schemas(): array
    {
        if (null === $this->expandedSchemas) {
            throw new \LogicException('Cannot access schemas() on a non-expanded ParseResult.');
        }

        return $this->expandedSchemas;
    }
}
