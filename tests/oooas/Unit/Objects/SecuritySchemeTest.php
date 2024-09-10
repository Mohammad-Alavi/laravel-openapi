<?php

namespace Tests\oooas\Unit\Objects;

use MohammadAlavi\ObjectOrientedOAS\Objects\Components;
use MohammadAlavi\ObjectOrientedOAS\Objects\OAuthFlow;
use MohammadAlavi\ObjectOrientedOAS\Objects\SecurityScheme;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(SecurityScheme::class)]
class SecuritySchemeTest extends UnitTestCase
{
        public function test_create_with_all_parameters_works()
    {
        $oauthFlow = OAuthFlow::create()
            ->flow(OAuthFlow::FLOW_CLIENT_CREDENTIALS);

        $securityScheme = SecurityScheme::create('OAuth2')
            ->type(SecurityScheme::TYPE_OAUTH2)
            ->description('Standard auth')
            ->in(SecurityScheme::IN_HEADER)
            ->scheme('basic')
            ->bearerFormat('JWT')
            ->flows($oauthFlow)
            ->openIdConnectUrl('https://goldspecdigital.com');

        $components = Components::create()
            ->securitySchemes($securityScheme);

        $this->assertEquals([
            'securitySchemes' => [
                'OAuth2' => [
                    'type' => 'oauth2',
                    'description' => 'Standard auth',
                    'in' => 'header',
                    'scheme' => 'basic',
                    'bearerFormat' => 'JWT',
                    'flows' => [
                        'clientCredentials' => [],
                    ],
                    'openIdConnectUrl' => 'https://goldspecdigital.com',
                ],
            ],
        ], $components->toArray());
    }
}
