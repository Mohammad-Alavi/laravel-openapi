<?php

namespace MohammadAlavi\Laragen\RequestSchema\ExampleGenerator;

final readonly class ExampleRegistry
{
    /**
     * @param array<string, class-string<Example>> $examples
     */
    public function __construct(
        private array $examples,
    ) {
    }

    /**
     * @return class-string<Example>|null
     */
    public function get(string $rule): string|null
    {
        return $this->examples[$rule] ?? null;
    }

    public function has(string $rule): bool
    {
        return array_key_exists($rule, $this->examples);
    }
}
