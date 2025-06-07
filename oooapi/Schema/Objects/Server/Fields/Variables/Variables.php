<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;

final readonly class Variables implements \JsonSerializable
{
    private function __construct(
        private array $variables,
    ) {
    }

    public static function create(Entry ...$entry): self
    {
        return new self($entry);
    }

    /** @return array<string, ServerVariable>|null */
    public function jsonSerialize(): array|null
    {
        if ([] === $this->variables) {
            return null;
        }

        return array_reduce(
            $this->variables,
            static fn (array $carry, Entry $entry): array => array_merge($carry, $entry->value()),
            [],
        );
    }
}
