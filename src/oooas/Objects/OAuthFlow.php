<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property string|null $flow
 * @property string|null $authorizationUrl
 * @property string|null $tokenUrl
 * @property string|null $refreshUrl
 * @property array|null $scopes
 */
class OAuthFlow extends BaseObject
{
    public const FLOW_IMPLICIT = 'implicit';
    public const FLOW_PASSWORD = 'password';
    public const FLOW_CLIENT_CREDENTIALS = 'clientCredentials';
    public const FLOW_AUTHORIZATION_CODE = 'authorizationCode';

    /**
     * @var string|null
     */
    protected $flow;

    /**
     * @var string|null
     */
    protected $authorizationUrl;

    /**
     * @var string|null
     */
    protected $tokenUrl;

    /**
     * @var string|null
     */
    protected $refreshUrl;

    /**
     * @var array|null
     */
    protected $scopes;

    /**
     * @return static
     */
    public function flow(string|null $flow): self
    {
        $instance = clone $this;

        $instance->flow = $flow;

        return $instance;
    }

    /**
     * @return static
     */
    public function authorizationUrl(string|null $authorizationUrl): self
    {
        $instance = clone $this;

        $instance->authorizationUrl = $authorizationUrl;

        return $instance;
    }

    /**
     * @return static
     */
    public function tokenUrl(string|null $tokenUrl): self
    {
        $instance = clone $this;

        $instance->tokenUrl = $tokenUrl;

        return $instance;
    }

    /**
     * @return static
     */
    public function refreshUrl(string|null $refreshUrl): self
    {
        $instance = clone $this;

        $instance->refreshUrl = $refreshUrl;

        return $instance;
    }

    /**
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public function scopes(array|null $scopes): self
    {
        // Ensure the scopes are string => string.
        foreach ($scopes as $key => $value) {
            if (is_string($key) && is_string($value)) {
                continue;
            }

            throw new InvalidArgumentException('Each scope must have a string key and a string value.');
        }

        $instance = clone $this;

        $instance->scopes = $scopes;

        return $instance;
    }

    protected function generate(): array
    {
        return Arr::filter([
            'authorizationUrl' => $this->authorizationUrl,
            'tokenUrl' => $this->tokenUrl,
            'refreshUrl' => $this->refreshUrl,
            'scopes' => $this->scopes,
        ]);
    }
}
