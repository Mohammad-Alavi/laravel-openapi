<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Fields\Deprecated;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Fields\OperationId;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Fields\Summary;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameters;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

final class Operation extends ExtensibleObject
{
    // TODO: refactor all const everywhere to enum
    public const ACTION_GET = 'get';
    public const ACTION_PUT = 'put';
    public const ACTION_POST = 'post';
    public const ACTION_DELETE = 'delete';
    public const ACTION_OPTIONS = 'options';
    public const ACTION_HEAD = 'head';
    public const ACTION_PATCH = 'patch';
    public const ACTION_TRACE = 'trace';

    private string|null $method = null;
    /** @var string[]|null */
    private array|null $tags = null;
    private Summary|null $summary = null;
    private Description|null $description = null;
    private ExternalDocumentation|null $externalDocs = null;
    private OperationId|null $operationId = null;
    private Parameters|null $parameterCollection = null;
    private RequestBody|RequestBodyFactory|null $requestBody = null;
    private Responses|null $responses = null;
    private Deprecated|null $deprecated = null;
    private Security|null $security = null;
    private bool|null $noSecurity = null;
    /** @var Server[]|null */
    private array|null $servers = null;
    /** @var (Callback|CallbackFactory)[]|null */
    private array|null $callbacks = null;

    public static function create(): self
    {
        return new self();
    }

    public static function get(): self
    {
        return self::create()->action(self::ACTION_GET);
    }

    public function action(string $action): self
    {
        $clone = clone $this;

        $clone->method = $action;

        return $clone;
    }

    public static function put(): self
    {
        return self::create()->action(self::ACTION_PUT);
    }

    public static function post(): self
    {
        return self::create()->action(self::ACTION_POST);
    }

    public static function delete(): self
    {
        return self::create()->action(self::ACTION_DELETE);
    }

    public static function options(): self
    {
        return self::create()->action(self::ACTION_OPTIONS);
    }

    public static function head(): self
    {
        return self::create()->action(self::ACTION_HEAD);
    }

    public static function patch(): self
    {
        return self::create()->action(self::ACTION_PATCH);
    }

    public static function trace(): self
    {
        return self::create()->action(self::ACTION_TRACE);
    }

    public function method(): string
    {
        return $this->method;
    }

    public function tags(Tag|string ...$tags): self
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

    public function summary(Summary $summary): self
    {
        $clone = clone $this;

        $clone->summary = $summary;

        return $clone;
    }

    public function description(Description $description): self
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function externalDocs(ExternalDocumentation $externalDocs): self
    {
        $clone = clone $this;

        $clone->externalDocs = $externalDocs;

        return $clone;
    }

    public function operationId(OperationId $operationId): self
    {
        $clone = clone $this;

        $clone->operationId = $operationId;

        return $clone;
    }

    public function parameters(Parameters $parameterCollection): self
    {
        $clone = clone $this;

        $clone->parameterCollection = $parameterCollection->toNullIfEmpty();

        return $clone;
    }

    public function requestBody(RequestBody|RequestBodyFactory|null $requestBody): self
    {
        $clone = clone $this;

        $clone->requestBody = $requestBody;

        return $clone;
    }

    public function responses(Responses|null $responses): self
    {
        $clone = clone $this;

        $clone->responses = $responses;

        return $clone;
    }

    public function deprecated(): self
    {
        $clone = clone $this;

        $clone->deprecated = Deprecated::yes();

        return $clone;
    }

    public function security(Security $security): self
    {
        $clone = clone $this;

        $clone->security = $security;

        return $clone;
    }

    public function noSecurity(): self
    {
        $clone = clone $this;

        $clone->noSecurity = true;

        return $clone;
    }

    public function servers(Server ...$server): self
    {
        $clone = clone $this;

        $clone->servers = [] !== $server ? $server : null;

        return $clone;
    }

    public function callbacks(Callback|CallbackFactory ...$callback): self
    {
        $clone = clone $this;

        $clone->callbacks = [] !== $callback ? $callback : null;

        return $clone;
    }

    protected function toArray(): array
    {
        $callbacks = [];
        foreach ($this->callbacks ?? [] as $callback) {
            if ($callback instanceof CallbackFactory) {
                $object = $callback->build();
                $callbacks[$object->key()] = $object;
            } else {
                $callbacks[$callback->key()] = $callback;
            }
        }

        return Arr::filter([
            'tags' => $this->tags,
            'summary' => $this->summary,
            'description' => $this->description,
            'externalDocs' => $this->externalDocs,
            'operationId' => $this->operationId,
            'parameters' => $this->parameterCollection,
            'requestBody' => $this->requestBody,
            'responses' => $this->responses,
            'deprecated' => $this->deprecated,
            'security' => $this->security,
            'servers' => $this->servers,
            'callbacks' => [] !== $callbacks ? $callbacks : null,
        ]);
    }
}
