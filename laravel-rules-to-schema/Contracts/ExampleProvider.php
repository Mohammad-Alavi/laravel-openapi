<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Contracts;

interface ExampleProvider
{
    public function has(string $rule): bool;

    /** @return list<mixed> */
    public function get(string $rule): array;
}
