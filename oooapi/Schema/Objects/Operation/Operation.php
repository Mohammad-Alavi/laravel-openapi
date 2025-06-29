<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Fields\OperationId;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Summary;

final class Operation extends ExtensibleObject
{
    private Summary|null $summary = null;
    private Description|null $description = null;
    private ExternalDocumentation|null $externalDocs = null;
    private OperationId|null $operationId = null;
    private Parameters|null $parameters = null;
    private RequestBody|RequestBodyFactory|null $requestBody = null;
    private Responses|null $responses = null;
    private Security|null $security = null;
    private true|null $deprecated = null;

    /** @var string[]|null */
    private array|null $tags = null;

    /** @var Server[]|null */
    private array|null $servers = null;

    /** @var Callback[]|null */
    private array|null $callbacks = null;

    public function tags(Tag|string ...$tags): self
    {
        $allStringTags = array_map(
            static function (Tag|string $tag): string {
                if ($tag instanceof Tag) {
                    return (string) $tag;
                }

                return $tag;
            },
            $tags,
        );

        $clone = clone $this;

        $clone->tags = when(blank($allStringTags), null, $allStringTags);

        return $clone;
    }

    public function summary(string $summary): self
    {
        $clone = clone $this;

        $clone->summary = Summary::create($summary);

        return $clone;
    }

    public static function create(): self
    {
        return new self();
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public function externalDocs(ExternalDocumentation $externalDocs): self
    {
        $clone = clone $this;

        $clone->externalDocs = $externalDocs;

        return $clone;
    }

    public function operationId(string $operationId): self
    {
        $clone = clone $this;

        $clone->operationId = OperationId::create($operationId);

        return $clone;
    }

    public function parameters(Parameters $parameters): self
    {
        $clone = clone $this;

        $clone->parameters = $parameters->toNullIfEmpty();

        return $clone;
    }

    public function requestBody(RequestBody|RequestBodyFactory $requestBody): self
    {
        $clone = clone $this;

        $clone->requestBody = $requestBody;

        return $clone;
    }

    public function responses(Responses $responses): self
    {
        $clone = clone $this;

        $clone->responses = $responses;

        return $clone;
    }

    public function deprecated(): self
    {
        $clone = clone $this;

        $clone->deprecated = true;

        return $clone;
    }

    public function security(Security $security): self
    {
        $clone = clone $this;

        $clone->security = $security;

        return $clone;
    }

    public function servers(Server ...$server): self
    {
        $clone = clone $this;

        $clone->servers = when(blank($server), null, $server);

        return $clone;
    }

    public function callbacks(Callback|CallbackFactory ...$callback): self
    {
        $clone = clone $this;

        foreach ($callback as $item) {
            if ($item instanceof CallbackFactory) {
                $clone->callbacks[$item::name()] = $item->component();
            }

            if ($item instanceof Callback) {
                $clone->callbacks[$item->name()] = $item;
            }
        }

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'tags' => $this->tags,
            'summary' => $this->summary,
            'description' => $this->description,
            'externalDocs' => $this->externalDocs,
            'operationId' => $this->operationId,
            'parameters' => $this->parameters,
            'requestBody' => $this->requestBody,
            'responses' => $this->responses,
            'deprecated' => $this->deprecated,
            'security' => $this->security,
            'servers' => $this->servers,
            'callbacks' => $this->callbacks,
        ]);
    }
}
