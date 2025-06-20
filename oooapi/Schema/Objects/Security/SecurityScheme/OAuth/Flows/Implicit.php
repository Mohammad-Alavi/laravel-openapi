<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Flows;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Flow;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\ScopeCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

final readonly class Implicit extends Flow
{
    private function __construct(
        private string $authorizationUrl,
        string|null $refreshUrl,
        ScopeCollection|null $scopeCollection,
    ) {
        parent::__construct($refreshUrl, $scopeCollection);
    }

    public static function create(
        string $authorizationUrl,
        string|null $refreshUrl = null,
        ScopeCollection|null $scopeCollection = null,
    ): self {
        return new self($authorizationUrl, $refreshUrl, $scopeCollection);
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'authorizationUrl' => $this->authorizationUrl,
            'refreshUrl' => $this->refreshUrl,
            'scopes' => $this->scopeCollection,
        ]);
    }
}
