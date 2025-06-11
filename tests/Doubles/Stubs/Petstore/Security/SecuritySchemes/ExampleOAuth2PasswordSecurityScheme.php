<?php

namespace Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\OAuth\Flows;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\OAuth\Flows\Password;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\OAuth\ScopeCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Schemes\OAuth2;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme;
use Tests\Doubles\Stubs\Petstore\Security\Scopes\OrderItemScope;
use Tests\Doubles\Stubs\Petstore\Security\Scopes\OrderPaymentScope;
use Tests\Doubles\Stubs\Petstore\Security\Scopes\OrderScope;
use Tests\Doubles\Stubs\Petstore\Security\Scopes\OrderShippingAddressScope;
use Tests\Doubles\Stubs\Petstore\Security\Scopes\OrderShippingStatusScope;

class ExampleOAuth2PasswordSecurityScheme extends SecuritySchemeFactory
{
    public static function key(): string
    {
        return 'OAuth2Password';
    }

    public function build(): SecurityScheme
    {
        return OAuth2::create(
            Flows::create(
                password: Password::create(
                    'https://example.com/oauth/authorize',
                    'https://example.com/oauth/token',
                    ScopeCollection::create(
                        OrderScope::create(),
                        OrderItemScope::create(),
                        OrderPaymentScope::create(),
                        OrderShippingAddressScope::create(),
                        OrderShippingStatusScope::create(),
                    ),
                ),
            ),
        );
    }
}
