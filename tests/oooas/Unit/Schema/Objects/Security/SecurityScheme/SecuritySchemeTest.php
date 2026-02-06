<?php

namespace Tests\oooas\Unit\Schema\Objects\Security\SecurityScheme;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Flows\ClientCredentials;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\OAuthFlows;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\ApiKey;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\OAuth2;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;

describe(class_basename(SecurityScheme::class), function (): void {
    it('can be created with apiKey', function (): void {
        $scheme = SecurityScheme::apiKey(
            ApiKey::header('X-API-Key'),
        );

        expect($scheme->compile())->toBe([
            'type' => 'apiKey',
            'name' => 'X-API-Key',
            'in' => 'header',
        ]);
    });

    it('can be created with http bearer', function (): void {
        $scheme = SecurityScheme::http(
            Http::bearer('JWT'),
        );

        expect($scheme->compile())->toBe([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
        ]);
    });

    it('can be created with oauth2', function (): void {
        $scheme = SecurityScheme::oAuth2(
            OAuth2::create(
                OAuthFlows::create(
                    clientCredentials: ClientCredentials::create(
                        'https://example.com/oauth/token',
                    ),
                ),
            ),
        );

        expect($scheme->compile()['type'])->toBe('oauth2')
            ->and($scheme->compile())->toHaveKey('flows');
    });

    it('can be created with openIdConnect', function (): void {
        $scheme = SecurityScheme::openIdConnect(
            'https://example.com/.well-known/openid-configuration',
        );

        expect($scheme->compile())->toBe([
            'type' => 'openIdConnect',
            'openIdConnectUrl' => 'https://example.com/.well-known/openid-configuration',
        ]);
    });

    it('can have description', function (): void {
        $scheme = SecurityScheme::apiKey(
            ApiKey::header('X-API-Key'),
        )->description('API key for authentication');

        expect($scheme->compile())->toBe([
            'type' => 'apiKey',
            'description' => 'API key for authentication',
            'name' => 'X-API-Key',
            'in' => 'header',
        ]);
    });

    it('can be marked as deprecated (OAS 3.2)', function (): void {
        $scheme = SecurityScheme::apiKey(
            ApiKey::header('X-API-Key'),
        )->deprecated();

        expect($scheme->compile())->toBe([
            'type' => 'apiKey',
            'deprecated' => true,
            'name' => 'X-API-Key',
            'in' => 'header',
        ]);
    });

    it('can have both description and deprecated (OAS 3.2)', function (): void {
        $scheme = SecurityScheme::http(
            Http::basic(),
        )
            ->description('Basic auth - deprecated, use OAuth2 instead')
            ->deprecated();

        expect($scheme->compile())->toBe([
            'type' => 'http',
            'description' => 'Basic auth - deprecated, use OAuth2 instead',
            'deprecated' => true,
            'scheme' => 'basic',
        ]);
    });

    it('deprecated defaults to not being set (OAS 3.2)', function (): void {
        $scheme = SecurityScheme::apiKey(
            ApiKey::header('X-API-Key'),
        );

        expect($scheme->compile())->not->toHaveKey('deprecated');
    });
})->covers(SecurityScheme::class);
