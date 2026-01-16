<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "$defs" keyword provides a location for reusable schema definitions.
 *
 * Schemas defined here can be referenced via "$ref": "#/$defs/name".
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-8.2.4
 */
final readonly class Defs implements Keyword
{
    /** @param Def[] $defs */
    private function __construct(
        private array $defs,
    ) {
    }

    public static function create(Def ...$def): self
    {
        return new self($def);
    }

    public static function name(): string
    {
        return '$defs';
    }

    /**
     * @return Def[]
     */
    public function value(): array
    {
        return $this->defs;
    }

    public function jsonSerialize(): array
    {
        $defs = [];
        foreach ($this->value() as $def) {
            $defs[$def->name()] = $def->schema();
        }

        return $defs;
    }
}
