<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property string|null $action
 * @property string[]|null $tags
 * @property string|null $summary
 * @property string|null $description
 * @property ExternalDocs|null $externalDocs
 * @property string|null $operationId
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Parameter[]|null $parameters
 * @property RequestBody|null $requestBody
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Response[]|null $responses
 * @property bool|null $deprecated
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\SecurityRequirement[]|null $security
 * @property bool|null $noSecurity
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Server[]|null $servers
 */
class Operation extends BaseObject
{
    public const ACTION_GET = 'get';
    public const ACTION_PUT = 'put';
    public const ACTION_POST = 'post';
    public const ACTION_DELETE = 'delete';
    public const ACTION_OPTIONS = 'options';
    public const ACTION_HEAD = 'head';
    public const ACTION_PATCH = 'patch';
    public const ACTION_TRACE = 'trace';

    /**
     * @var string|null
     */
    protected $action;

    /**
     * @var string[]|null
     */
    protected $tags;

    /**
     * @var string|null
     */
    protected $summary;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var ExternalDocs|null
     */
    protected $externalDocs;

    /**
     * @var string|null
     */
    protected $operationId;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Parameter[]|null
     */
    protected $parameters;

    /**
     * @var RequestBody|null
     */
    protected $requestBody;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Response[]|null
     */
    protected $responses;

    /**
     * @var bool|null
     */
    protected $deprecated;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\SecurityRequirement[]|null
     */
    protected $security;

    /**
     * @var bool|null
     */
    protected $noSecurity;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Server[]|null
     */
    protected $servers;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\PathItem[]|null
     */
    protected $callbacks;

    /**
     * @return static
     */
    public static function get(string|null $objectId = null): self
    {
        return static::create($objectId)->action(static::ACTION_GET);
    }

    /**
     * @return static
     */
    public static function put(string|null $objectId = null): self
    {
        return static::create($objectId)->action(static::ACTION_PUT);
    }

    /**
     * @return static
     */
    public static function post(string|null $objectId = null): self
    {
        return static::create($objectId)->action(static::ACTION_POST);
    }

    /**
     * @return static
     */
    public static function delete(string|null $objectId = null): self
    {
        return static::create($objectId)->action(static::ACTION_DELETE);
    }

    /**
     * @return static
     */
    public static function head(string|null $objectId = null): self
    {
        return static::create($objectId)->action(static::ACTION_HEAD);
    }

    /**
     * @return static
     */
    public static function patch(string|null $objectId = null): self
    {
        return static::create($objectId)->action(static::ACTION_PATCH);
    }

    /**
     * @return static
     */
    public static function trace(string|null $objectId = null): self
    {
        return static::create($objectId)->action(static::ACTION_TRACE);
    }

    /**
     * @return static
     */
    public function action(string|null $action): self
    {
        $instance = clone $this;

        $instance->action = $action;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Tag[]|string[] $tags
     *
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public function tags(...$tags): self
    {
        // Only allow Tag instances and strings.
        foreach ($tags as &$tag) {
            // If a Tag instance was passed in then extract it's name string.
            if ($tag instanceof Tag) {
                $tag = $tag->name;
                continue;
            }

            if (is_string($tag)) {
                continue;
            }

            throw new InvalidArgumentException(sprintf('The tags must either be a string or an instance of [%s].', Tag::class));
        }

        $instance = clone $this;

        $instance->tags = $tags ?: null;

        return $instance;
    }

    /**
     * @return static
     */
    public function summary(string|null $summary): self
    {
        $instance = clone $this;

        $instance->summary = $summary;

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
    public function externalDocs(ExternalDocs|null $externalDocs): self
    {
        $instance = clone $this;

        $instance->externalDocs = $externalDocs;

        return $instance;
    }

    /**
     * @return static
     */
    public function operationId(string|null $operationId): self
    {
        $instance = clone $this;

        $instance->operationId = $operationId;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Parameter[] $parameters
     *
     * @return static
     */
    public function parameters(Parameter ...$parameters): self
    {
        $instance = clone $this;

        $instance->parameters = $parameters ?: null;

        return $instance;
    }

    /**
     * @return static
     */
    public function requestBody(RequestBody|null $requestBody): self
    {
        $instance = clone $this;

        $instance->requestBody = $requestBody;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Response[] $responses
     *
     * @return static
     */
    public function responses(Response ...$responses): self
    {
        $instance = clone $this;

        $instance->responses = $responses;

        return $instance;
    }

    /**
     * @return static
     */
    public function deprecated(bool|null $deprecated = true): self
    {
        $instance = clone $this;

        $instance->deprecated = $deprecated;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\SecurityRequirement[]|null $security
     *
     * @return static
     */
    public function security(SecurityRequirement ...$security): self
    {
        $instance = clone $this;

        $instance->security = $security ?: null;
        $instance->noSecurity = null;

        return $instance;
    }

    /**
     * @return static
     */
    public function noSecurity(bool|null $noSecurity = true): self
    {
        $instance = clone $this;

        $instance->noSecurity = $noSecurity;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Server[] $servers
     *
     * @return static
     */
    public function servers(Server ...$servers): self
    {
        $instance = clone $this;

        $instance->servers = $servers ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\PathItem[] $callbacks
     *
     * @return $this
     */
    public function callbacks(PathItem ...$callbacks): self
    {
        $instance = clone $this;

        $instance->callbacks = $callbacks ?: null;

        return $instance;
    }

    protected function generate(): array
    {
        $responses = [];
        foreach ($this->responses ?? [] as $response) {
            $responses[$response->statusCode ?? 'default'] = $response;
        }

        $callbacks = [];
        foreach ($this->callbacks ?? [] as $callback) {
            $callbacks[$callback->objectId][$callback->route] = $callback;
        }

        return Arr::filter([
            'tags' => $this->tags,
            'summary' => $this->summary,
            'description' => $this->description,
            'externalDocs' => $this->externalDocs,
            'operationId' => $this->operationId,
            'parameters' => $this->parameters,
            'requestBody' => $this->requestBody,
            'responses' => $responses ?: null,
            'deprecated' => $this->deprecated,
            'security' => $this->noSecurity ? [] : $this->security,
            'servers' => $this->servers,
            'callbacks' => $callbacks ?: null,
        ]);
    }
}
