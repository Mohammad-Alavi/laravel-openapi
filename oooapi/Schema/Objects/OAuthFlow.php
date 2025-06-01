<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Exceptions\InvalidArgumentException;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

// TODO: there are 2 different objects OAuthFlow and OAuthFlows, but we only have one!
//  https://swagger.io/specification/#oauth-flows-object
class OAuthFlow extends ExtensibleObject implements SimpleCreator
{
    use SimpleCreatorTrait;

    public const FLOW_IMPLICIT = 'implicit';
    public const FLOW_PASSWORD = 'password';
    public const FLOW_CLIENT_CREDENTIALS = 'clientCredentials';
    public const FLOW_AUTHORIZATION_CODE = 'authorizationCode';

    protected string|null $flow = null;
    protected string|null $authorizationUrl = null;
    protected string|null $tokenUrl = null;
    protected string|null $refreshUrl = null;
    protected array|null $scopes = null;

    public function flow(string|null $flow): static
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
