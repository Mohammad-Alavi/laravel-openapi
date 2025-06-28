<?php

namespace Tests\src\Support\Doubles\Stubs\Petstore\Security\SecuritySchemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Flows\Password;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\OAuthFlows;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\ScopeCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\OAuth2;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\Scopes\OrderItemScope;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\Scopes\OrderPaymentScope;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\Scopes\OrderScope;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\Scopes\OrderShippingAddressScope;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\Scopes\OrderShippingStatusScope;

class TestOAuth2PasswordSecuritySchemeFactory extends SecuritySchemeFactory
{
    public static function name(): string
    {
        return 'OAuth2Password';
    }

    public function component(): SecurityScheme
    {
        return SecurityScheme::oAuth2(
            OAuth2::create(
                OAuthFlows::create(
                    password: Password::create(
                        'https://laragen.io/oauth/authorize',
                        'https://laragen.io/oauth/token',
                        ScopeCollection::create(
                            OrderScope::create(),
                            OrderItemScope::create(),
                            OrderPaymentScope::create(),
                            OrderShippingAddressScope::create(),
                            OrderShippingStatusScope::create(),
                        ),
                    ),
                ),
            ),
            Description::create('OAuth2 Password Security'),
        );
    }
}
