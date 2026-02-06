<?php

namespace Tests\oooas\Unit\Schema\Objects\Security;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\RequiredSecurity;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Flows\ClientCredentials;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\OAuthFlows;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\ScopeCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\ApiKey;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\OAuth2;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;
use Tests\oooas\Support\Factories\Scopes\AdminScopeFactory;
use Tests\oooas\Support\Factories\Scopes\ReadScopeFactory;
use Tests\oooas\Support\Factories\Scopes\ReadUsersScopeFactory;
use Tests\oooas\Support\Factories\Scopes\WriteUsersScopeFactory;

describe(class_basename(SecurityRequirement::class), function (): void {
    it('can be created empty for anonymous access', function (): void {
        $requirement = SecurityRequirement::create();

        expect($requirement->compile())->toBe([]);
    });

    it('can be created with single security scheme', function (): void {
        $factory = new class extends SecuritySchemeFactory {
            public function component(): SecurityScheme
            {
                return SecurityScheme::apiKey(ApiKey::header('X-API-Key'));
            }

            public static function name(): string
            {
                return 'ApiKeyAuth';
            }
        };

        $requirement = SecurityRequirement::create(
            RequiredSecurity::create($factory),
        );

        expect($requirement->compile())->toBe([
            'ApiKeyAuth' => [],
        ]);
    });

    it('can be created with multiple security schemes (AND relationship)', function (): void {
        $apiKeyFactory = new class extends SecuritySchemeFactory {
            public function component(): SecurityScheme
            {
                return SecurityScheme::apiKey(ApiKey::header('X-API-Key'));
            }

            public static function name(): string
            {
                return 'ApiKeyAuth';
            }
        };

        $basicFactory = new class extends SecuritySchemeFactory {
            public function component(): SecurityScheme
            {
                return SecurityScheme::http(Http::basic());
            }

            public static function name(): string
            {
                return 'BasicAuth';
            }
        };

        $requirement = SecurityRequirement::create(
            RequiredSecurity::create($apiKeyFactory),
            RequiredSecurity::create($basicFactory),
        );

        expect($requirement->compile())->toBe([
            'ApiKeyAuth' => [],
            'BasicAuth' => [],
        ]);
    });

    it('can be created with OAuth2 and scopes', function (): void {
        $factory = new class extends SecuritySchemeFactory {
            public function component(): SecurityScheme
            {
                return SecurityScheme::oAuth2(
                    OAuth2::create(
                        OAuthFlows::create(
                            clientCredentials: ClientCredentials::create(
                                'https://example.com/oauth/token',
                                scopeCollection: ScopeCollection::create(
                                    ReadUsersScopeFactory::create(),
                                    WriteUsersScopeFactory::create(),
                                ),
                            ),
                        ),
                    ),
                );
            }

            public static function name(): string
            {
                return 'OAuth2Auth';
            }
        };

        $requirement = SecurityRequirement::create(
            RequiredSecurity::create(
                $factory,
                ScopeCollection::create(ReadUsersScopeFactory::create()),
            ),
        );

        expect($requirement->compile())->toBe([
            'OAuth2Auth' => ['read:users'],
        ]);
    });
})->covers(SecurityRequirement::class);

describe(class_basename(RequiredSecurity::class), function (): void {
    it('can be created without scopes', function (): void {
        $factory = new class extends SecuritySchemeFactory {
            public function component(): SecurityScheme
            {
                return SecurityScheme::apiKey(ApiKey::header('X-API-Key'));
            }

            public static function name(): string
            {
                return 'ApiKeyAuth';
            }
        };

        $required = RequiredSecurity::create($factory);

        expect($required->scheme())->toBe('ApiKeyAuth')
            ->and($required->scopes())->toBeEmpty();
    });

    it('can be created with scopes for non-OAuth2 scheme', function (): void {
        $factory = new class extends SecuritySchemeFactory {
            public function component(): SecurityScheme
            {
                return SecurityScheme::http(Http::bearer('JWT'));
            }

            public static function name(): string
            {
                return 'BearerAuth';
            }
        };

        $required = RequiredSecurity::create(
            $factory,
            ScopeCollection::create(AdminScopeFactory::create()),
        );

        expect($required->scheme())->toBe('BearerAuth')
            ->and($required->scopes())->toHaveCount(1);
    });

    it('can be created with OAuth2 and scopes', function (): void {
        $factory = new class extends SecuritySchemeFactory {
            public function component(): SecurityScheme
            {
                return SecurityScheme::oAuth2(
                    OAuth2::create(
                        OAuthFlows::create(
                            clientCredentials: ClientCredentials::create(
                                'https://example.com/oauth/token',
                                scopeCollection: ScopeCollection::create(ReadScopeFactory::create()),
                            ),
                        ),
                    ),
                );
            }

            public static function name(): string
            {
                return 'OAuth2Auth';
            }
        };

        $required = RequiredSecurity::create(
            $factory,
            ScopeCollection::create(ReadScopeFactory::create()),
        );

        expect($required->scopes())->toHaveCount(1)
            ->and($required->scheme())->toBe('OAuth2Auth');
    });

    it('returns correct scheme name', function (): void {
        $factory = new class extends SecuritySchemeFactory {
            public function component(): SecurityScheme
            {
                return SecurityScheme::http(Http::basic());
            }

            public static function name(): string
            {
                return 'MyCustomAuth';
            }
        };

        $required = RequiredSecurity::create($factory);

        expect($required->scheme())->toBe('MyCustomAuth');
    });
})->covers(RequiredSecurity::class);
