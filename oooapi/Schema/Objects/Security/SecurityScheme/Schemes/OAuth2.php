<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\OAuthFlows;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Scope;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Contracts\Scheme;

final readonly class OAuth2 implements Scheme
{
    private function __construct(
        private OAuthFlows $OAuthFlows,
    ) {
    }

    public static function create(OAuthFlows $OAuthFlows): self
    {
        return new self($OAuthFlows);
    }

    public function type(): string
    {
        return 'oauth2';
    }

    public function containsAllScopes(Scope ...$scope): bool
    {
        return $this->OAuthFlows->scopeCollection()->containsAll(...$scope);
    }

    public function availableScopes(): array
    {
        return $this->OAuthFlows->scopeCollection()->all();
    }

    public function toArray(): array
    {
        return [
            'flows' => $this->OAuthFlows,
        ];
    }
}
