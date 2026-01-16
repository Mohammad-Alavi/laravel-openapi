<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Contracts\Scheme;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Fields\OpenIdConnectUrl;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\ApiKey;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\MutualTLS;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\OAuth2;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\OpenIdConnect;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;

/**
 * Security Scheme Object.
 *
 * Defines a security scheme that can be used by the operations. Supported
 * schemes are HTTP authentication, API key, mutual TLS, OAuth2, and
 * OpenID Connect Discovery.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#security-scheme-object
 */
final class SecurityScheme extends ExtensibleObject
{
    private Description|null $description = null;

    private function __construct(
        private readonly Scheme $scheme,
    ) {
    }

    public static function apiKey(ApiKey $apiKey): self
    {
        return new self($apiKey);
    }

    public static function http(Http $http): self
    {
        return new self($http);
    }

    public static function mutualTLS(MutualTLS $mutualTLS): self
    {
        return new self($mutualTLS);
    }

    public static function oAuth2(OAuth2 $oAuth2): self
    {
        return new self($oAuth2);
    }

    public static function openIdConnect(string $openIdConnectUrl): self
    {
        return new self(OpenIdConnect::create(OpenIdConnectUrl::create($openIdConnectUrl)));
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'type' => $this->scheme->type(),
            'description' => $this->description,
            ...$this->scheme->toArray(),
        ]);
    }
}
