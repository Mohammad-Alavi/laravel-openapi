<?php

namespace App\Domain\Documentation\Access\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class Scope
{
    public string $pattern;

    public function __construct(string $pattern)
    {
        Assert::notEmpty($pattern, 'Scope pattern cannot be empty.');

        $this->pattern = $pattern;
    }

    public function matches(string $identifier): bool
    {
        return fnmatch($this->pattern, $identifier);
    }

    public function hasWildcard(): bool
    {
        return str_contains($this->pattern, '*');
    }

    public function toString(): string
    {
        return $this->pattern;
    }
}
