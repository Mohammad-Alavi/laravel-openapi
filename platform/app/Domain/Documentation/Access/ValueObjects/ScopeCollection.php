<?php

namespace App\Domain\Documentation\Access\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class ScopeCollection
{
    /** @var list<Scope> */
    private array $scopes;

    /** @param list<Scope> $scopes */
    public function __construct(array $scopes = [])
    {
        Assert::allIsInstanceOf($scopes, Scope::class);

        $this->scopes = array_values($scopes);
    }

    /** @param list<string> $patterns */
    public static function fromArray(array $patterns): self
    {
        return new self(array_map(
            static fn (string $pattern): Scope => new Scope($pattern),
            $patterns,
        ));
    }

    public function matchesAny(string $identifier): bool
    {
        foreach ($this->scopes as $scope) {
            if ($scope->matches($identifier)) {
                return true;
            }
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return $this->scopes === [];
    }

    public function count(): int
    {
        return count($this->scopes);
    }

    public function hasWildcards(): bool
    {
        foreach ($this->scopes as $scope) {
            if ($scope->hasWildcard()) {
                return true;
            }
        }

        return false;
    }

    /** @return list<string> */
    public function toArray(): array
    {
        return array_map(
            static fn (Scope $scope): string => $scope->toString(),
            $this->scopes,
        );
    }
}
