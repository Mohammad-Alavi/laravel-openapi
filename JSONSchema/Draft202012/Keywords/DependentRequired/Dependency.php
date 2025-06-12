<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentRequired;

final readonly class Dependency
{
    /**
     * @param string[] $dependencies
     */
    private function __construct(
        private string $property,
        private array $dependencies,
    ) {
    }

    public static function create(string $property, string ...$dependsOn): self
    {
        return new self($property, $dependsOn);
    }

    public function property(): string
    {
        return $this->property;
    }

    /**
     * @return string[]
     */
    public function dependencies(): array
    {
        return $this->dependencies;
    }
}
