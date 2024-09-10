<?php

namespace Tests\oooas\Unit\Objects;

use MohammadAlavi\ObjectOrientedOAS\Objects\OAuthFlow;
use MohammadAlavi\ObjectOrientedOAS\Objects\SecurityScheme;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\UnitTestCase;

#[CoversClass(OAuthFlow::class)]
class OAuthFlowTest extends UnitTestCase
{
        public function test_create_with_all_parameters_works()
    {
        $oauthFlow = OAuthFlow::create()
            ->flow(OAuthFlow::FLOW_AUTHORIZATION_CODE)
            ->authorizationUrl('https://api.goldspecdigital.com/oauth/authorization')
            ->tokenUrl('https://api.goldspecdigital.com/oauth/token')
            ->refreshUrl('https://api.goldspecdigital.com/oauth/token')
            ->scopes(['read:user' => 'Read the user profile']);

        $securityScheme = SecurityScheme::create()
            ->flows($oauthFlow);

        $this->assertEquals([
            'flows' => [
                'authorizationCode' => [
                    'authorizationUrl' => 'https://api.goldspecdigital.com/oauth/authorization',
                    'tokenUrl' => 'https://api.goldspecdigital.com/oauth/token',
                    'refreshUrl' => 'https://api.goldspecdigital.com/oauth/token',
                    'scopes' => [
                        'read:user' => 'Read the user profile',
                    ],
                ],
            ],
        ], $securityScheme->toArray());
    }
}
