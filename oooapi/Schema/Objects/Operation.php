<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\LaravelOpenApi\Collections\ParameterCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

class Operation extends ExtensibleObject implements SimpleCreator
{
    use SimpleCreatorTrait;

    // TODO: refactor all const everywhere to enum
    public const ACTION_GET = 'get';
    public const ACTION_PUT = 'put';
    public const ACTION_POST = 'post';
    public const ACTION_DELETE = 'delete';
    public const ACTION_OPTIONS = 'options';
    public const ACTION_HEAD = 'head';
    public const ACTION_PATCH = 'patch';
    public const ACTION_TRACE = 'trace';

    protected string|null $method = null;

    /** @var string[]|null */
    protected array|null $tags = null;

    protected string|null $summary = null;
    protected string|null $description = null;
    protected ExternalDocumentation|null $externalDocs = null;
    protected string|null $operationId = null;
    protected ParameterCollection|null $parameterCollection = null;
    protected RequestBody|Reference|null $requestBody = null;
    protected Responses|null $responses = null;
    protected bool|null $deprecated = null;
    protected Security|null $security = null;
    protected bool|null $noSecurity = null;

    /** @var Server[]|null */
    protected array|null $servers = null;

    /** @var Callback[]|null */
    protected array|null $callbacks = null;

    public static function get(): static
    {
        return static::create()->action(static::ACTION_GET);
    }

    public function action(string $action): static
    {
        $clone = clone $this;

        $clone->method = $action;

        return $clone;
    }

    public static function put(): static
    {
        return static::create()->action(static::ACTION_PUT);
    }

    public static function post(): static
    {
        return static::create()->action(static::ACTION_POST);
    }

    public static function delete(): static
    {
        return static::create()->action(static::ACTION_DELETE);
    }

    public static function options(): static
    {
        return static::create()->action(static::ACTION_OPTIONS);
    }

    public static function head(): static
    {
        return static::create()->action(static::ACTION_HEAD);
    }

    public static function patch(): static
    {
        return static::create()->action(static::ACTION_PATCH);
    }

    public static function trace(): static
    {
        return static::create()->action(static::ACTION_TRACE);
    }

    public function method(): string
    {
        return $this->method;
    }

    public function tags(Tag|string ...$tags): static
    {
        $allStringTags = array_map(static function (Tag|string $tag): string {
            if ($tag instanceof Tag) {
                return (string) $tag;
            }

            return $tag;
        }, $tags);

        $clone = clone $this;

        $clone->tags = [] !== $allStringTags ? $allStringTags : null;

        return $clone;
    }

    public function summary(string|null $summary): static
    {
        $clone = clone $this;

        $clone->summary = $summary;

        return $clone;
    }

    public function description(string|null $description): static
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function externalDocs(ExternalDocumentation|null $externalDocs): static
    {
        $clone = clone $this;

        $clone->externalDocs = $externalDocs;

        return $clone;
    }

    public function operationId(string|null $operationId): static
    {
        $clone = clone $this;

        $clone->operationId = $operationId;

        return $clone;
    }

    public function parameters(ParameterCollection $parameterCollection): static
    {
        $clone = clone $this;

        $clone->parameterCollection = $parameterCollection->toNullIfEmpty();

        return $clone;
    }

    public function requestBody(RequestBody|Reference|null $requestBody): static
    {
        $clone = clone $this;

        $clone->requestBody = $requestBody;

        return $clone;
    }

    public function responses(Responses|null $responses): static
    {
        $clone = clone $this;

        $clone->responses = $responses;

        return $clone;
    }

    public function deprecated(bool|null $deprecated = true): static
    {
        $clone = clone $this;

        $clone->deprecated = $deprecated;

        return $clone;
    }

    public function security(Security $security): static
    {
        $clone = clone $this;

        $clone->security = $security;

        return $clone;
    }

    public function noSecurity(): static
    {
        $clone = clone $this;

        $clone->noSecurity = true;

        return $clone;
    }

    public function servers(Server ...$server): static
    {
        $clone = clone $this;

        $clone->servers = [] !== $server ? $server : null;

        return $clone;
    }

    public function callbacks(Callback ...$callback): static
    {
        $clone = clone $this;

        $clone->callbacks = [] !== $callback ? $callback : null;

        return $clone;
    }

    protected function toArray(): array
    {
        $callbacks = [];
        foreach ($this->callbacks ?? [] as $callback) {
            $callbacks[$callback->key()] = $callback;
        }

        return Arr::filter([
            'tags' => $this->tags,
            'summary' => $this->summary,
            'description' => $this->description,
            'externalDocs' => $this->externalDocs,
            'operationId' => $this->operationId,
            'parameters' => $this->parameterCollection,
            'requestBody' => $this->requestBody,
            'responses' => $this->responses ?? Responses::create(),
            'deprecated' => $this->deprecated,
            'security' => $this->security,
            'servers' => $this->servers,
            'callbacks' => [] !== $callbacks ? $callbacks : null,
        ]);
    }
}
