<?php

namespace MohammadAlavi\Laragen\Auth;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\RequiredSecurity;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;

final class SecuritySchemeRegistry
{
    public function securityFor(AuthScheme $authScheme): Security
    {
        return Security::create(
            SecurityRequirement::create(
                RequiredSecurity::create($this->factoryFor($authScheme)),
            ),
        );
    }

    public function factoryFor(AuthScheme $authScheme): SecuritySchemeFactory
    {
        return match ($authScheme->guardName()) {
            null => BasicSecuritySchemeFactory::create(),
            default => BearerSecuritySchemeFactory::create(),
        };
    }
}
