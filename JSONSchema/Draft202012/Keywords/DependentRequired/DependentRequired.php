<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentRequired;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

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
