<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OAuthFlow;

use MohammadAlavi\ObjectOrientedOpenAPI\Exceptions\InvalidArgumentException;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OAuthFlow\Fields\AuthorizationUrl;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OAuthFlow\Fields\Flow;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OAuthFlow\Fields\RefreshUrl;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OAuthFlow\Fields\TokenUrl;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

final class OAuthFlow extends ExtensibleObject
{
    protected Flow|null $flow = null;
    private AuthorizationUrl $authorizationUrl;
    private TokenUrl $tokenUrl;
    private RefreshUrl|null $refreshUrl = null;
    private array|null $scopes = null;

    public static function create(): self
    {
        return new self();
    }

    public function flow(Flow|null $flow): static
    {
        $clone = clone $this;

        $clone->flow = $flow;

        return $clone;
    }

    public function authorizationUrl(string|null $authorizationUrl): static
    {
        $clone = clone $this;

        $clone->authorizationUrl = $authorizationUrl;

        return $clone;
    }

    public function tokenUrl(string|null $tokenUrl): static
    {
        $clone = clone $this;

        $clone->tokenUrl = $tokenUrl;

        return $clone;
    }

    public function refreshUrl(string|null $refreshUrl): static
    {
        $clone = clone $this;

        $clone->refreshUrl = $refreshUrl;

        return $clone;
    }

    /**
     * @param array<string, string>|null $scopes
     *
     * @throws InvalidArgumentException
     */
    public function scopes(array|null $scopes): static
    {
        if (is_array($scopes)) {
            foreach ($scopes as $key => $value) {
                if (!is_string($key) || !is_string($value)) {
                    throw new InvalidArgumentException('Each scope must have a string key and a string value.');
                }
            }
        }

        $clone = clone $this;

        $clone->scopes = $scopes;

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'authorizationUrl' => $this->authorizationUrl,
            'tokenUrl' => $this->tokenUrl,
            'refreshUrl' => $this->refreshUrl,
            'scopes' => $this->scopes,
        ]);
    }
}
