<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping;

final readonly class Mapping implements \JsonSerializable
{
    private function __construct(
        private array $mappings,
    ) {
    }

    public static function create(Entry ...$entry): self
    {
        return new self($entry);
    }

    /** @return array<string, string>|null */
    public function jsonSerialize(): array|null
    {
        if ([] === $this->mappings) {
            return null;
        }

        return array_reduce(
            $this->mappings,
            static fn (array $carry, Entry $entry): array => array_merge($carry, $entry->value()),
            [],
        );
    }
}
