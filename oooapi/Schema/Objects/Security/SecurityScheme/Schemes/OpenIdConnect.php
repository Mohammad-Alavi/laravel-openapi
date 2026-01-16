<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Contracts\Scheme;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Fields\OpenIdConnectUrl;

final readonly class OpenIdConnect implements Scheme
{
    private function __construct(
        private OpenIdConnectUrl $openIdConnectUrl,
    ) {
    }

    public static function create(OpenIdConnectUrl $openIdConnectUrl): self
    {
        return new self($openIdConnectUrl);
    }

    public function type(): string
    {
        return 'openIdConnect';
    }

    public function jsonSerialize(): array|null
    {
        return [
            'openIdConnectUrl' => $this->openIdConnectUrl,
        ];
    }
}
