<?php

namespace App\Domain\Documentation\Access\ValueObjects;

use Illuminate\Support\Str;

final readonly class PlainToken
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function generate(): self
    {
        return new self(Str::random(64));
    }

    public function hashed(): HashedToken
    {
        return HashedToken::fromPlain($this->value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
