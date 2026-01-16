<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentRequired;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "dependentRequired" keyword defines property dependencies.
 *
 * When a property is present, other specified properties become required.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-6.5.4
 */
final readonly class DependentRequired implements Keyword
{
    /** @param Dependency[] $dependencies */
    private function __construct(
        private array $dependencies,
    ) {
    }

    public static function create(Dependency ...$dependency): self
    {
        return new self($dependency);
    }

    public static function name(): string
    {
        return 'dependentRequired';
    }

    public function jsonSerialize(): array
    {
        $deps = [];
        foreach ($this->value() as $dependency) {
            $deps[$dependency->property()] = $dependency->dependencies();
        }

        return $deps;
    }

    /**
     * @return Dependency[]
     */
    public function value(): array
    {
        return $this->dependencies;
    }
}
