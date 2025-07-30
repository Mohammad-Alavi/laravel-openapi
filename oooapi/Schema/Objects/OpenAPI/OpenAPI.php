<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Info;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\Fields\JsonSchemaDialect;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\Fields\OpenAPI as OpenAPIField;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class OpenAPI extends ExtensibleObject
{
    private JsonSchemaDialect $jsonSchemaDialect;
    private Paths|null $paths = null;
    private Components|null $components = null;
    private Security|null $security = null;
    private ExternalDocumentation|null $externalDocs = null;

    /** @var Server[] */
    private array $servers = [];

    /** @var Tag[]|null */
    private array|null $tags = null;

    private function __construct(
        private readonly OpenAPIField $openAPIField,
        private readonly Info $info,
    ) {
        $this->jsonSchemaDialect = JsonSchemaDialect::v31x();
    }

    public static function v311(
        Info $info,
    ): self {
        return new self(OpenAPIField::v311(), $info);
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

    public function getPaths(): Paths|null
    {
        return $this->paths;
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

        $clone->tags = when(blank($tag), null, $tag);

        return $clone;
    }

    public function externalDocs(ExternalDocumentation $externalDocumentation): self
    {
        $clone = clone $this;

        $clone->externalDocs = $externalDocumentation;

        return $clone;
    }

    public function jsonSerialize(): array
    {
        $this->beforeSerialization();

        return parent::jsonSerialize();
    }

    private function beforeSerialization(): void
    {
        // Ensure that the Components object is properly initialized with references to all reusable components
        //  used in the OpenAPI document.
        $this->components = Components::from($this, $this->components);
    }

    public function toArray(): array
    {
        return Arr::filter([
            'openapi' => $this->openAPIField,
            'info' => $this->info,
            'jsonSchemaDialect' => $this->jsonSchemaDialect,
            'servers' => when(blank($this->servers), [Server::default()], $this->servers),
            'paths' => $this->toObjectIfEmpty($this->paths),
            'components' => $this->toObjectIfEmpty($this->components),
            'security' => $this->security,
            'tags' => $this->tags,
            'externalDocs' => $this->externalDocs,
        ]);
    }
}
