<?php

namespace Tests\oooas\Unit\Schema\Objects\Security\SecurityScheme;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Flows\AuthorizationCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Flows\ClientCredentials;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Flows\DeviceAuthorization;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Flows\Implicit;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Flows\Password;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\OAuthFlows;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\ScopeCollection;
use Tests\oooas\Support\Factories\Scopes\ApiAccessScopeFactory;
use Tests\oooas\Support\Factories\Scopes\DeviceAccessScopeFactory;
use Tests\oooas\Support\Factories\Scopes\DeviceScopeFactory;
use Tests\oooas\Support\Factories\Scopes\OfflineAccessScopeFactory;
use Tests\oooas\Support\Factories\Scopes\OpenIdScopeFactory;
use Tests\oooas\Support\Factories\Scopes\ProfileScopeFactory;
use Tests\oooas\Support\Factories\Scopes\ReadScopeFactory;
use Tests\oooas\Support\Factories\Scopes\WriteScopeFactory;

describe(class_basename(OAuthFlows::class), function (): void {
    it('can create flows with all types', function (): void {
        $flows = OAuthFlows::create(
            implicit: Implicit::create(
                'https://example.com/oauth/authorize',
            ),
            password: Password::create(
                'https://example.com/oauth/token',
            ),
            clientCredentials: ClientCredentials::create(
                'https://example.com/oauth/token',
            ),
            authorizationCode: AuthorizationCode::create(
                'https://example.com/oauth/authorize',
                'https://example.com/oauth/token',
            ),
            deviceAuthorization: DeviceAuthorization::create(
                'https://example.com/oauth/device',
                'https://example.com/oauth/token',
            ),
        );

        expect($flows->compile())->toHaveKeys([
            'implicit',
            'password',
            'clientCredentials',
            'authorizationCode',
            'deviceAuthorization',
        ]);
    });

    it('can add deviceAuthorization flow via fluent method', function (): void {
        $flows = OAuthFlows::create()
            ->deviceAuthorization(DeviceAuthorization::create(
                'https://example.com/oauth/device',
                'https://example.com/oauth/token',
            ));

        expect($flows->compile())->toBe([
            'deviceAuthorization' => [
                'deviceAuthorizationUrl' => 'https://example.com/oauth/device',
                'tokenUrl' => 'https://example.com/oauth/token',
                'scopes' => [],
            ],
        ]);
    });

    it('collects scopes from all flows including deviceAuthorization', function (): void {
        $flows = OAuthFlows::create(
            implicit: Implicit::create(
                'https://example.com/oauth/authorize',
                scopeCollection: ScopeCollection::create(ReadScopeFactory::create()),
            ),
            deviceAuthorization: DeviceAuthorization::create(
                'https://example.com/oauth/device',
                'https://example.com/oauth/token',
                scopeCollection: ScopeCollection::create(DeviceScopeFactory::create()),
            ),
        );

        expect($flows->scopeCollection()->all())->toHaveCount(2);
    });
})->covers(OAuthFlows::class);

describe(class_basename(DeviceAuthorization::class), function (): void {
    it('can be created with required parameters (OAS 3.2)', function (): void {
        $flow = DeviceAuthorization::create(
            'https://example.com/oauth/device',
            'https://example.com/oauth/token',
        );

        expect($flow->compile())->toBe([
            'deviceAuthorizationUrl' => 'https://example.com/oauth/device',
            'tokenUrl' => 'https://example.com/oauth/token',
            'scopes' => [],
        ]);
    });

    it('can be created with all parameters (OAS 3.2)', function (): void {
        $flow = DeviceAuthorization::create(
            deviceAuthorizationUrl: 'https://example.com/oauth/device',
            tokenUrl: 'https://example.com/oauth/token',
            refreshUrl: 'https://example.com/oauth/refresh',
            scopeCollection: ScopeCollection::create(
                DeviceAccessScopeFactory::create(),
                OfflineAccessScopeFactory::create(),
            ),
        );

        expect($flow->compile())->toBe([
            'deviceAuthorizationUrl' => 'https://example.com/oauth/device',
            'tokenUrl' => 'https://example.com/oauth/token',
            'refreshUrl' => 'https://example.com/oauth/refresh',
            'scopes' => [
                'device:access' => 'Device access',
                'offline_access' => 'Offline access',
            ],
        ]);
    });

    it('can retrieve scope collection', function (): void {
        $flow = DeviceAuthorization::create(
            'https://example.com/oauth/device',
            'https://example.com/oauth/token',
            scopeCollection: ScopeCollection::create(DeviceScopeFactory::create()),
        );

        expect($flow->scopeCollection()->all())->toHaveCount(1);
    });
})->covers(DeviceAuthorization::class);

describe(class_basename(Password::class), function (): void {
    it('can be created with required parameters', function (): void {
        $flow = Password::create(
            'https://example.com/oauth/token',
        );

        expect($flow->compile())->toBe([
            'tokenUrl' => 'https://example.com/oauth/token',
            'scopes' => [],
        ]);
    });

    it('can be created with all parameters', function (): void {
        $flow = Password::create(
            tokenUrl: 'https://example.com/oauth/token',
            refreshUrl: 'https://example.com/oauth/refresh',
            scopeCollection: ScopeCollection::create(
                ReadScopeFactory::create(),
                WriteScopeFactory::create(),
            ),
        );

        expect($flow->compile())->toBe([
            'tokenUrl' => 'https://example.com/oauth/token',
            'refreshUrl' => 'https://example.com/oauth/refresh',
            'scopes' => [
                'read' => 'Read access',
                'write' => 'Write access',
            ],
        ]);
    });
})->covers(Password::class);

describe(class_basename(ClientCredentials::class), function (): void {
    it('can be created with required parameters', function (): void {
        $flow = ClientCredentials::create(
            'https://example.com/oauth/token',
        );

        expect($flow->compile())->toBe([
            'tokenUrl' => 'https://example.com/oauth/token',
            'scopes' => [],
        ]);
    });

    it('can be created with all parameters', function (): void {
        $flow = ClientCredentials::create(
            tokenUrl: 'https://example.com/oauth/token',
            refreshUrl: 'https://example.com/oauth/refresh',
            scopeCollection: ScopeCollection::create(ApiAccessScopeFactory::create()),
        );

        expect($flow->compile())->toBe([
            'tokenUrl' => 'https://example.com/oauth/token',
            'refreshUrl' => 'https://example.com/oauth/refresh',
            'scopes' => [
                'api:access' => 'API access',
            ],
        ]);
    });
})->covers(ClientCredentials::class);

describe(class_basename(AuthorizationCode::class), function (): void {
    it('can be created with required parameters', function (): void {
        $flow = AuthorizationCode::create(
            'https://example.com/oauth/authorize',
            'https://example.com/oauth/token',
        );

        expect($flow->compile())->toBe([
            'authorizationUrl' => 'https://example.com/oauth/authorize',
            'tokenUrl' => 'https://example.com/oauth/token',
            'scopes' => [],
        ]);
    });

    it('can be created with all parameters', function (): void {
        $flow = AuthorizationCode::create(
            authorizationUrl: 'https://example.com/oauth/authorize',
            tokenUrl: 'https://example.com/oauth/token',
            refreshUrl: 'https://example.com/oauth/refresh',
            scopeCollection: ScopeCollection::create(ProfileScopeFactory::create()),
        );

        expect($flow->compile())->toBe([
            'authorizationUrl' => 'https://example.com/oauth/authorize',
            'tokenUrl' => 'https://example.com/oauth/token',
            'refreshUrl' => 'https://example.com/oauth/refresh',
            'scopes' => [
                'profile' => 'Profile access',
            ],
        ]);
    });
})->covers(AuthorizationCode::class);

describe(class_basename(Implicit::class), function (): void {
    it('can be created with required parameters', function (): void {
        $flow = Implicit::create(
            'https://example.com/oauth/authorize',
        );

        expect($flow->compile())->toBe([
            'authorizationUrl' => 'https://example.com/oauth/authorize',
            'scopes' => [],
        ]);
    });

    it('can be created with all parameters', function (): void {
        $flow = Implicit::create(
            authorizationUrl: 'https://example.com/oauth/authorize',
            refreshUrl: 'https://example.com/oauth/refresh',
            scopeCollection: ScopeCollection::create(OpenIdScopeFactory::create()),
        );

        expect($flow->compile())->toBe([
            'authorizationUrl' => 'https://example.com/oauth/authorize',
            'refreshUrl' => 'https://example.com/oauth/refresh',
            'scopes' => [
                'openid' => 'OpenID access',
            ],
        ]);
    });
})->covers(Implicit::class);
