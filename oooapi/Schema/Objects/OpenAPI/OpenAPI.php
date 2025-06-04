<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocs;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\Fields\JsonSchemaDialect;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\Fields\OpenAPI as OpenAPIField;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

final class OpenAPI extends ExtensibleObject
{
    private JsonSchemaDialect $jsonSchemaDialect;
    private Paths|null $paths = null;
    private Components|null $components = null;
    private Security|null $security = null;
    /** @var Server[] */
    private array $servers = [];
    /** @var Tag[]|null */
    private array|null $tags = null;
    private ExternalDocs|null $externalDocs = null;

    private function __construct(
        private OpenAPIField $openapi,
        private Info $info,
    ) {
        $this->jsonSchemaDialect = JsonSchemaDialect::v31x();
    }

    public static function v311(
        Info $info,
    ): self {
        return self::create(OpenAPIField::v311(), $info);
    }

    public static function create(
        OpenAPIField $openapi,
        Info $info,
    ): self {
        return new self($openapi, $info);
    }

    public function openapi(OpenAPIField $openapi): self
    {
        $clone = clone $this;

        $clone->openapi = $openapi;

        return $clone;
    }

    public function info(Info $info): self
    {
        $clone = clone $this;

        $clone->info = $info;

        return $clone;
    }

    public function jsonSchemaDialect(JsonSchemaDialect $jsonSchemaDialect): self
    {
        $clone = clone $this;

        $clone->jsonSchemaDialect = $jsonSchemaDialect;

        return $clone;
    }

    public function servers(Server ...$server): self
    {
        $clone = clone $this;

        $clone->servers = $server;

        return $clone;
    }

    public function paths(Paths $paths): self
    {
        $clone = clone $this;

        $clone->paths = $paths;

        return $clone;
    }

    public function components(Components $components): self
    {
        $clone = clone $this;

        $clone->components = $components;

        return $clone;
    }

    public function security(Security $security): self
    {
        $clone = clone $this;

        $clone->security = $security;

        return $clone;
    }

    public function tags(Tag ...$tag): self
    {
        $clone = clone $this;

        $clone->tags = [] !== $tag ? $tag : null;

        return $clone;
    }

    public function externalDocs(ExternalDocs $externalDocs): self
    {
        $clone = clone $this;

        $clone->externalDocs = $externalDocs;

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'openapi' => $this->openapi,
            'info' => $this->info,
            'jsonSchemaDialect' => $this->jsonSchemaDialect,
            'servers' => $this->serversOrDefault(),
            'paths' => $this->toObjectIfEmpty($this->paths),
            'components' => $this->toObjectIfEmpty($this->components),
            'security' => $this->security,
            'tags' => $this->tags,
            'externalDocs' => $this->externalDocs,
        ]);
    }

    private function serversOrDefault(): array
    {
        if ([] === $this->servers) {
            return [Server::default()];
        }

        return $this->servers;
    }
}
