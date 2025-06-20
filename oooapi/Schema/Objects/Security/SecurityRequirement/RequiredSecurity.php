<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Scope;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\ScopeCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\OAuth2;

final readonly class RequiredSecurity
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
        OAuth2 $oAuth2,
        ScopeCollection $scopeCollection,
    ): self {
        if ($oAuth2->containsAllScopes(...$scopeCollection->all())) {
            return new self($securitySchemeFactory, $scopeCollection);
        }

        throw new \InvalidArgumentException("Invalid OAuth2 scopes for {$securitySchemeFactory::name()}.\nAvailable scopes: " . implode(', ', $oAuth2->availableScopes()) . "\nGiven scopes: " . array_reduce($scopeCollection->all(), static function (string $carry, Scope $scope): string { return $carry . $scope->name() . ', '; }, ''));
    }

    public function scheme(): string
    {
        return $this->securitySchemeFactory::name();
    }

    /**
     * @return Scope[]
     */
    public function scopes(): array
    {
        return $this->scopeCollection->all();
    }
}
