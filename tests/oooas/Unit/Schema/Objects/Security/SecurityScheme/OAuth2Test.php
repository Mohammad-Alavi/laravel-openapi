<?php

namespace Tests\oooas\Unit\Schema\Objects\Security\SecurityScheme;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Flows\ClientCredentials;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\OAuthFlows;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\ScopeCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\OAuth2;
use Tests\oooas\Support\Factories\Scopes\ReadUsersScopeFactory;
use Tests\oooas\Support\Factories\Scopes\WriteUsersScopeFactory;

describe(class_basename(OAuth2::class), function (): void {
    it('can be created without oauth2MetadataUrl', function (): void {
        $oauth2 = OAuth2::create(
            OAuthFlows::create(
                clientCredentials: ClientCredentials::create(
                    'https://example.com/oauth/token',
                ),
            ),
        );

        expect($oauth2->type())->toBe('oauth2')
            ->and($oauth2->jsonSerialize())->toBe([
                'flows' => [
                    'clientCredentials' => [
                        'tokenUrl' => 'https://example.com/oauth/token',
                        'scopes' => [],
                    ],
                ],
            ]);
    });

    it('can be created with oauth2MetadataUrl (OAS 3.2)', function (): void {
        $oauth2 = OAuth2::create(
            OAuthFlows::create(
                clientCredentials: ClientCredentials::create(
                    'https://example.com/oauth/token',
                ),
            ),
            'https://example.com/.well-known/oauth-authorization-server',
        );

        expect($oauth2->type())->toBe('oauth2')
            ->and($oauth2->jsonSerialize())->toBe([
                'oauth2MetadataUrl' => 'https://example.com/.well-known/oauth-authorization-server',
                'flows' => [
                    'clientCredentials' => [
                        'tokenUrl' => 'https://example.com/oauth/token',
                        'scopes' => [],
                    ],
                ],
            ]);
    });

    it('can validate scopes', function (): void {
        $oauth2 = OAuth2::create(
            OAuthFlows::create(
                clientCredentials: ClientCredentials::create(
                    'https://example.com/oauth/token',
                    scopeCollection: ScopeCollection::create(
                        ReadUsersScopeFactory::create(),
                        WriteUsersScopeFactory::create(),
                    ),
                ),
            ),
        );

        expect($oauth2->availableScopes())->toHaveCount(2);
    });
})->covers(OAuth2::class);
