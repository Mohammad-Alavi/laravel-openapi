<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Fields\Links;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;

final readonly class Links implements \JsonSerializable
{
    private function __construct(
        private array $links,
    ) {
    }

    public static function create(Entry ...$entry): self
    {
        return new self($entry);
    }

    /** @return array<string, Link>|null */
    public function jsonSerialize(): array|null
    {
        if ([] === $this->links) {
            return null;
        }

        return array_reduce(
            $this->links,
            static fn (array $carry, Entry $entry): array => array_merge($carry, $entry->value()),
            [],
        );
    }
}
