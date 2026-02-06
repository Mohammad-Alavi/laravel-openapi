<?php

namespace Tests\oooas\Unit\Schema\Objects\Security\SecurityScheme;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Fields\OpenIdConnectUrl;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\ApiKey;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\MutualTLS;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\OpenIdConnect;

describe(class_basename(ApiKey::class), function (): void {
    it('can be created for query parameter', function (): void {
        $apiKey = ApiKey::query('api_key');

        expect($apiKey->type())->toBe('apiKey')
            ->and($apiKey->jsonSerialize())->toBe([
                'name' => 'api_key',
                'in' => 'query',
            ]);
    });

    it('can be created for header', function (): void {
        $apiKey = ApiKey::header('X-API-Key');

        expect($apiKey->type())->toBe('apiKey')
            ->and($apiKey->jsonSerialize())->toBe([
                'name' => 'X-API-Key',
                'in' => 'header',
            ]);
    });

    it('can be created for cookie', function (): void {
        $apiKey = ApiKey::cookie('session_token');

        expect($apiKey->type())->toBe('apiKey')
            ->and($apiKey->jsonSerialize())->toBe([
                'name' => 'session_token',
                'in' => 'cookie',
            ]);
    });
})->covers(ApiKey::class);

describe(class_basename(Http::class), function (): void {
    it('can be created with basic scheme', function (): void {
        $http = Http::basic();

        expect($http->type())->toBe('http')
            ->and($http->jsonSerialize()['scheme'])->toBe('basic');
    });

    it('can be created with bearer scheme without format', function (): void {
        $http = Http::bearer();

        expect($http->type())->toBe('http')
            ->and($http->jsonSerialize()['scheme'])->toBe('bearer');
    });

    it('can be created with bearer scheme with JWT format', function (): void {
        $http = Http::bearer('JWT');

        expect($http->type())->toBe('http')
            ->and($http->jsonSerialize())->toBe([
                'scheme' => 'bearer',
                'bearerFormat' => 'JWT',
            ]);
    });

    it('can be created with digest scheme', function (): void {
        $http = Http::digest();

        expect($http->type())->toBe('http')
            ->and($http->jsonSerialize()['scheme'])->toBe('digest');
    });

    it('can be created with dpop scheme', function (): void {
        $http = Http::dpop();

        expect($http->jsonSerialize()['scheme'])->toBe('dpop');
    });

    it('can be created with gnap scheme', function (): void {
        $http = Http::gnap();

        expect($http->jsonSerialize()['scheme'])->toBe('gnap');
    });

    it('can be created with hoba scheme', function (): void {
        $http = Http::hoba();

        expect($http->jsonSerialize()['scheme'])->toBe('HOBA');
    });

    it('can be created with mutual scheme', function (): void {
        $http = Http::mutual();

        expect($http->jsonSerialize()['scheme'])->toBe('Mutual');
    });

    it('can be created with negotiate scheme', function (): void {
        $http = Http::negotiate();

        expect($http->jsonSerialize()['scheme'])->toBe('Negotiate');
    });

    it('can be created with oAuth scheme', function (): void {
        $http = Http::oAuth();

        expect($http->jsonSerialize()['scheme'])->toBe('OAuth');
    });

    it('can be created with privateToken scheme', function (): void {
        $http = Http::privateToken();

        expect($http->jsonSerialize()['scheme'])->toBe('PrivateToken');
    });

    it('can be created with scramSha1 scheme', function (): void {
        $http = Http::scramSha1();

        expect($http->jsonSerialize()['scheme'])->toBe('SCRAM-SHA-1');
    });

    it('can be created with scramSha256 scheme', function (): void {
        $http = Http::scramSha256();

        expect($http->jsonSerialize()['scheme'])->toBe('SCRAM-SHA-256');
    });

    it('can be created with vapid scheme', function (): void {
        $http = Http::vapid();

        expect($http->jsonSerialize()['scheme'])->toBe('vapid');
    });
})->covers(Http::class);

describe(class_basename(MutualTLS::class), function (): void {
    it('can be created', function (): void {
        $mutualTLS = MutualTLS::create();

        expect($mutualTLS->type())->toBe('mutualTLS')
            ->and($mutualTLS->jsonSerialize())->toBe([]);
    });
})->covers(MutualTLS::class);

describe(class_basename(OpenIdConnect::class), function (): void {
    it('can be created with openIdConnectUrl', function (): void {
        $openIdConnectUrl = OpenIdConnectUrl::create('https://example.com/.well-known/openid-configuration');
        $openIdConnect = OpenIdConnect::create(
            $openIdConnectUrl,
        );

        expect($openIdConnect->type())->toBe('openIdConnect')
            ->and($openIdConnect->jsonSerialize())->toBe([
                'openIdConnectUrl' => $openIdConnectUrl,
            ])
            ->and(\Safe\json_encode($openIdConnect))->json()->toBe([
                'openIdConnectUrl' => 'https://example.com/.well-known/openid-configuration',
            ]);
    });
})->covers(OpenIdConnect::class);

describe(class_basename(OpenIdConnectUrl::class), function (): void {
    it('can be created with valid URL', function (): void {
        $url = OpenIdConnectUrl::create('https://example.com/.well-known/openid-configuration');

        expect($url->value())->toBe('https://example.com/.well-known/openid-configuration');
    });

    it('throws exception for invalid URL', function (): void {
        expect(fn () => OpenIdConnectUrl::create('not-a-valid-url'))
            ->toThrow(\InvalidArgumentException::class);
    });
})->covers(OpenIdConnectUrl::class);
