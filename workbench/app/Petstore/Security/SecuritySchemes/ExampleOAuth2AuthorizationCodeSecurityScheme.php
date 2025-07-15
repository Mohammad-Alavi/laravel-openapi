<?php

namespace Workbench\App\Petstore\Security\SecuritySchemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Flows\AuthorizationCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\OAuthFlows;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\ScopeCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\OAuth2;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;
use Workbench\App\Petstore\Security\Scopes\OrderItemScope;
use Workbench\App\Petstore\Security\Scopes\OrderPaymentScope;
use Workbench\App\Petstore\Security\Scopes\OrderScope;
use Workbench\App\Petstore\Security\Scopes\OrderShippingAddressScope;
use Workbench\App\Petstore\Security\Scopes\OrderShippingStatusScope;

class ExampleOAuth2AuthorizationCodeSecurityScheme extends SecuritySchemeFactory
{
    public function component(): SecurityScheme
    {
        return SecurityScheme::oAuth2(
            OAuth2::create(
                OAuthFlows::create(
                    authorizationCode: AuthorizationCode::create(
                        'https://laragen.io/oauth/authorize',
                        'https://laragen.io/oauth/token',
                        null,
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
        );
    }
}
