<?php

namespace MohammadAlavi\Laragen\Auth;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;

final readonly class AuthScheme
{
    private function __construct(
        private SecurityScheme $securityScheme,
        private string $schemeName,
        private string|null $guardName,
    ) {
    }

    public static function bearer(string $guardName): self
    {
        return new self(
            SecurityScheme::http(Http::bearer()),
            $guardName,
            $guardName,
        );
    }

    public static function basic(): self
    {
        return new self(
            SecurityScheme::http(Http::basic()),
            'basic',
            null,
        );
    }

    public function guardName(): string|null
    {
        return $this->guardName;
    }

    public function schemeName(): string
    {
        return $this->schemeName;
    }

    public function toSecurityScheme(): SecurityScheme
    {
        return $this->securityScheme;
    }

    public function equals(self $other): bool
    {
        return $this->schemeName === $other->schemeName;
    }
}
