<?php

namespace MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Security\Schemes;

use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Security\SecurityScheme;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

final readonly class MutualTLS extends SecurityScheme
{
    private function __construct(
        string|null $description = null,
    ) {
        parent::__construct('mutualTLS', $description);
    }

    public static function create(string|null $description = null): self
    {
        return new self($description);
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'type' => $this->type,
            'description' => $this->description,
        ]);
    }
}
