<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Contracts\Scheme;

final readonly class MutualTLS implements Scheme
{
    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    public function type(): string
    {
        return 'mutualTLS';
    }

    public function toArray(): array
    {
        return [];
    }
}
