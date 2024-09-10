<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property string|null $type
 * @property string|null $description
 * @property string|null $name
 * @property string|null $in
 * @property string|null $scheme
 * @property string|null $bearerFormat
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\OAuthFlow[]|null $flows
 * @property string|null $openIdConnectUrl
 */
class SecurityScheme extends BaseObject
{
    public const TYPE_API_KEY = 'apiKey';
    public const TYPE_HTTP = 'http';
    public const TYPE_OAUTH2 = 'oauth2';
    public const TYPE_OPEN_ID_CONNECT = 'openIdConnect';

    public const IN_QUERY = 'query';
    public const IN_HEADER = 'header';
    public const IN_COOKIE = 'cookie';

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $in;

    /**
     * @var string|null
     */
    protected $scheme;

    /**
     * @var string|null
     */
    protected $bearerFormat;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\OAuthFlow[]|null
     */
    protected $flows;

    /**
     * @var string|null
     */
    protected $openIdConnectUrl;

    /**
     * @return static
     */
    public static function oauth2(string|null $objectId = null): self
    {
        return static::create($objectId)->type(static::TYPE_OAUTH2);
    }

    /**
     * @return static
     */
    public function type(string|null $type): self
    {
        $instance = clone $this;

        $instance->type = $type;

        return $instance;
    }

    /**
     * @return static
     */
    public function description(string|null $description): self
    {
        $instance = clone $this;

        $instance->description = $description;

        return $instance;
    }

    /**
     * @return static
     */
    public function name(string|null $name): self
    {
        $instance = clone $this;

        $instance->name = $name;

        return $instance;
    }

    /**
     * @return static
     */
    public function in(string|null $in): self
    {
        $instance = clone $this;

        $instance->in = $in;

        return $instance;
    }

    /**
     * @return static
     */
    public function scheme(string|null $scheme): self
    {
        $instance = clone $this;

        $instance->scheme = $scheme;

        return $instance;
    }

    /**
     * @return static
     */
    public function bearerFormat(string|null $bearerFormat): self
    {
        $instance = clone $this;

        $instance->bearerFormat = $bearerFormat;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\OAuthFlow[] $flows
     *
     * @return static
     */
    public function flows(OAuthFlow ...$flows): self
    {
        $instance = clone $this;

        $instance->flows = $flows;

        return $instance;
    }

    /**
     * @return static
     */
    public function openIdConnectUrl(string|null $openIdConnectUrl): self
    {
        $instance = clone $this;

        $instance->openIdConnectUrl = $openIdConnectUrl;

        return $instance;
    }

    protected function generate(): array
    {
        $flows = [];
        foreach ($this->flows ?? [] as $flow) {
            $flows[$flow->flow] = $flow;
        }

        return Arr::filter([
            'type' => $this->type,
            'description' => $this->description,
            'name' => $this->name,
            'in' => $this->in,
            'scheme' => $this->scheme,
            'bearerFormat' => $this->bearerFormat,
            'flows' => $flows ?: null,
            'openIdConnectUrl' => $this->openIdConnectUrl,
        ]);
    }
}
