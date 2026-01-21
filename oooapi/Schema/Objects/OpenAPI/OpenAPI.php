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
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Webhooks\Webhooks;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Summary;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Validator;

/**
 * OpenAPI Object.
 *
 * This is the root object of the OpenAPI document.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#openapi-object
 */
final class OpenAPI extends ExtensibleObject
{
    private JsonSchemaDialect $jsonSchemaDialect;

    /** @var string|null JSON Pointer to self within a bundled document */
    private string|null $self = null;

    private Summary|null $summary = null;
    private Paths|null $paths = null;
    private Webhooks|null $webhooks = null;
    private Components|null $components = null;
    private Security|null $security = null;
    private ExternalDocumentation|null $externalDocs = null;

    /** @var Server[]|null */
    private array|null $servers = null;

    /** @var Tag[]|null */
    private array|null $tags = null;

    /** @var bool Flag to prevent re-crawling the object tree on subsequent serializations */
    private bool $referencesCollected = false;

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

    /**
     * A JSON Pointer to this OpenAPI Object within a bundled document.
     *
     * Used to identify the root of an OpenAPI description when it is bundled
     * with other documents (e.g., JSON Schema documents) that may also contain
     * "$id" keywords.
     *
     * @see https://spec.openapis.org/oas/v3.2.0#fixed-fields
     */
    public function self(string $jsonPointer): self
    {
        Validator::jsonPointer($jsonPointer);

        $clone = clone $this;

        $clone->self = $jsonPointer;

        return $clone;
    }

    /**
     * A short summary of the API.
     *
     * @see https://spec.openapis.org/oas/v3.2.0#fixed-fields
     */
    public function summary(string $summary): self
    {
        $clone = clone $this;

        $clone->summary = Summary::create($summary);

        return $clone;
    }

    public function servers(Server ...$server): self
    {
        $clone = clone $this;

        $clone->servers = when(blank($server), null, $server);

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

    /**
     * The incoming webhooks that MAY be received as part of this API.
     *
     * @see https://spec.openapis.org/oas/v3.2.0#fixed-fields
     */
    public function webhooks(Webhooks $webhooks): self
    {
        $clone = clone $this;

        $clone->webhooks = $webhooks;

        return $clone;
    }

    public function getWebhooks(): Webhooks|null
    {
        return $this->webhooks;
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
        if ($this->referencesCollected) {
            return;
        }

        $this->validateTagHierarchy();

        // Ensure that the Components object is properly initialized with references to all reusable components
        //  used in the OpenAPI document.
        $this->components = Components::from($this, $this->components);
        $this->referencesCollected = true;
    }

    /**
     * Validates the tag hierarchy according to OAS 3.2 requirements.
     *
     * @throws \InvalidArgumentException if a parent tag doesn't exist or circular references are detected
     */
    private function validateTagHierarchy(): void
    {
        if (null === $this->tags || [] === $this->tags) {
            return;
        }

        // Build a map of tag names for quick lookup
        $tagNames = [];
        foreach ($this->tags as $tag) {
            $tagNames[$tag->name()] = true;
        }

        // Build parent relationships and validate
        $parentMap = [];
        foreach ($this->tags as $tag) {
            $parentName = $tag->parentName();
            if (null === $parentName) {
                continue;
            }

            // Validate parent exists
            if (!isset($tagNames[$parentName])) {
                throw new \InvalidArgumentException(sprintf('Tag "%s" references parent tag "%s" which does not exist in the API description.', $tag->name(), $parentName));
            }

            $parentMap[$tag->name()] = $parentName;
        }

        // Detect circular references
        foreach ($parentMap as $tagName => $parentName) {
            $visited = [$tagName => true];
            $current = $parentName;

            while (null !== $current && isset($parentMap[$current])) {
                if (isset($visited[$current])) {
                    throw new \InvalidArgumentException(sprintf('Circular reference detected in tag hierarchy: tag "%s" is part of a cycle.', $tagName));
                }
                $visited[$current] = true;
                $current = $parentMap[$current];
            }
        }
    }

    public function toArray(): array
    {
        return Arr::filter([
            'openapi' => $this->openAPIField,
            '$self' => $this->self,
            'info' => $this->info,
            'summary' => $this->summary,
            'jsonSchemaDialect' => $this->jsonSchemaDialect,
            'servers' => when(blank($this->servers), [Server::default()], $this->servers),
            'paths' => $this->toObjectIfEmpty($this->paths),
            'webhooks' => is_null($this->webhooks) ? null : $this->toObjectIfEmpty($this->webhooks),
            'components' => $this->toObjectIfEmpty($this->components),
            'security' => $this->security,
            'tags' => $this->tags,
            'externalDocs' => $this->externalDocs,
        ]);
    }
}
