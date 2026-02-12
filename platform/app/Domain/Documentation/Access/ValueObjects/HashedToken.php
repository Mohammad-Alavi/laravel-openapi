<?php

namespace App\Domain\Documentation\Access\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class HashedToken
{
    public string $hash;

    public function __construct(string $hash)
    {
        Assert::notEmpty($hash, 'Token hash cannot be empty.');

        $this->hash = $hash;
    }

    public static function fromPlain(string $plainToken): self
    {
        return new self(hash('sha256', $plainToken));
    }

    public function equals(string $plainToken): bool
    {
        return hash_equals($this->hash, hash('sha256', $plainToken));
    }

    public function toString(): string
    {
        return $this->hash;
    }
}
