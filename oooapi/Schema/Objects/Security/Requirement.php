<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\OAuth\Scope;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\OAuth\ScopeCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Schemes\OAuth2;

final readonly class Requirement
{
    private function __construct(
        private SecuritySchemeFactory $securitySchemeFactory,
        private ScopeCollection $scopeCollection,
    ) {
    }

    public static function create(
        SecuritySchemeFactory $securitySchemeFactory,
        ScopeCollection|null $scopeCollection = null,
    ): self {
        if (is_null($scopeCollection)) {
            return new self($securitySchemeFactory, ScopeCollection::create());
        }

        $securityScheme = $securitySchemeFactory->build();
        if ($securityScheme instanceof OAuth2) {
            return self::createOAuth2Requirement($securitySchemeFactory, $securityScheme, $scopeCollection);
        }

        return new self($securitySchemeFactory, $scopeCollection);
    }

    private static function createOAuth2Requirement(
        SecuritySchemeFactory $securitySchemeFactory,
        OAuth2 $securityScheme,
        ScopeCollection $scopeCollection,
    ): self {
        if ($securityScheme->containsAllScopes(...$scopeCollection->all())) {
            return new self($securitySchemeFactory, $scopeCollection);
        }

        throw new \InvalidArgumentException("Invalid OAuth2 scopes for {$securitySchemeFactory::key()}.\nAvailable scopes: " . implode(', ', $securityScheme->availableScopes()) . "\nGiven scopes: " . array_reduce($scopeCollection->all(), static fn (string $carry, Scope $scope): string => $carry . $scope->name() . ', ', ''));
    }

    public function securityScheme(): string
    {
        return $this->securitySchemeFactory::key();
    }

    public function scopes(): array
    {
        return $this->scopeCollection->all();
    }
}
