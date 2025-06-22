<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Contracts\Scheme;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Fields\OpenIdConnectUrl;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\ApiKey;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\MutualTLS;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\OAuth2;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\OpenIdConnect;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

final class SecurityScheme extends ExtensibleObject
{
    private function __construct(
        private readonly Scheme $scheme,
        private readonly Description|null $description,
    ) {
    }

    public static function apiKey(ApiKey $apiKey, Description|null $description = null): self
    {
        return new self($apiKey, $description);
    }

    public static function http(Http $http, Description|null $description = null): self
    {
        return new self($http, $description);
    }

    public static function mutualTLS(MutualTLS $mutualTLS, Description|null $description = null): self
    {
        return new self($mutualTLS, $description);
    }

    public static function oAuth2(OAuth2 $OAuth2, Description|null $description = null): self
    {
        return new self($OAuth2, $description);
    }

    public static function openIdConnect(OpenIdConnectUrl $openIdConnectUrl, Description|null $description = null): self
    {
        return new self(OpenIdConnect::create($openIdConnectUrl), $description);
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'type' => $this->scheme->type(),
            'description' => $this->description,
            ...$this->scheme->toArray(),
        ]);
    }
}
