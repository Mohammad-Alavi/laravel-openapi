<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

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
            $defs[$def->name()] = $def->value();
        }

        return $defs;
    }
}
