<?php

namespace MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Security\Schemes;

use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Security\SecurityScheme;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

final readonly class OpenIdConnect extends SecurityScheme
{
    private function __construct(
        public string $openIdConnectUrl,
        string|null $description = null,
    ) {
        parent::__construct('openIdConnect', $description);
    }

    public static function create(string $openIdConnectUrl, string|null $description = null): self
    {
        return new self($openIdConnectUrl, $description);
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'type' => $this->type,
            'description' => $this->description,
            'openIdConnectUrl' => $this->openIdConnectUrl,
        ]);
    }
}
