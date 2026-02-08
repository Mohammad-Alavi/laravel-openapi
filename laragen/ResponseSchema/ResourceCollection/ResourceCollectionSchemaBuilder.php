<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema\ResourceCollection;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;
use MohammadAlavi\Laragen\ResponseSchema\JsonResource\JsonResourceSchemaBuilder;
use MohammadAlavi\Laragen\ResponseSchema\ResponseSchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Compilable;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use Webmozart\Assert\Assert;

final readonly class ResourceCollectionSchemaBuilder implements ResponseSchemaBuilder
{
    public function __construct(
        private JsonResourceSchemaBuilder $jsonResourceBuilder,
    ) {
    }

    public function build(string $responseClass): JSONSchema
    {
        /** @var class-string<ResourceCollection> $responseClass */
        $innerResourceClass = $this->resolveInnerResourceClass($responseClass);
        Assert::notNull($innerResourceClass, "Could not resolve inner resource class for {$responseClass}.");

        $innerSchema = $this->unwrapResourceSchema(
            $this->jsonResourceBuilder->build($innerResourceClass),
            $innerResourceClass,
        );
        $arraySchema = Schema::array()->items($innerSchema);

        $wrapKey = $this->getWrapKey($responseClass);

        if (null === $wrapKey) {
            return $arraySchema;
        }

        return Schema::object()->properties(
            Property::create($wrapKey, $arraySchema),
        );
    }

    /**
     * Remove the wrap key from a JsonResource schema to get the raw object schema.
     *
     * JsonResourceSchemaBuilder wraps output in {data: {…}} by default.
     * For collection items, we need the unwrapped inner object schema.
     *
     * @param class-string<JsonResource> $resourceClass
     */
    private function unwrapResourceSchema(JSONSchema $schema, string $resourceClass): JSONSchema
    {
        $resourceWrapKey = $this->getResourceWrapKey($resourceClass);

        if (null === $resourceWrapKey) {
            return $schema;
        }

        Assert::isInstanceOf($schema, Compilable::class);
        /** @var array<string, mixed> $compiled */
        $compiled = $schema->compile();

        /** @var array<string, array<string, mixed>> $properties */
        $properties = $compiled['properties'] ?? [];

        if (isset($properties[$resourceWrapKey])) {
            /** @var array<string, mixed> $innerCompiled */
            $innerCompiled = $properties[$resourceWrapKey];

            return Schema::from($innerCompiled);
        }

        return $schema;
    }

    /**
     * @param class-string<JsonResource> $resourceClass
     */
    private function getResourceWrapKey(string $resourceClass): string|null
    {
        $reflection = new \ReflectionClass($resourceClass);
        $wrapProperty = $reflection->getProperty('wrap');

        /** @var string|null $wrap */
        $wrap = $wrapProperty->getValue();

        return $wrap;
    }

    /**
     * Resolve the inner resource class from a ResourceCollection.
     *
     * Mirrors Laravel's CollectsResources::collects() logic:
     * 1. If $collects property is explicitly set, use it
     * 2. If class name ends with "Collection", try replacing with "" or "Resource"
     *
     * @param class-string<ResourceCollection> $collectionClass
     *
     * @return class-string<JsonResource>|null
     */
    private function resolveInnerResourceClass(string $collectionClass): string|null
    {
        $reflection = new \ReflectionClass($collectionClass);
        $collectsProperty = $reflection->getProperty('collects');
        /** @var class-string|null $collects */
        $collects = $collectsProperty->getDefaultValue();

        if (null !== $collects && is_subclass_of($collects, JsonResource::class)) {
            return $collects;
        }

        if (str_ends_with(class_basename($collectionClass), 'Collection')) {
            $withoutSuffix = Str::replaceLast('Collection', '', $collectionClass);

            if (class_exists($withoutSuffix) && is_subclass_of($withoutSuffix, JsonResource::class)) {
                return $withoutSuffix;
            }

            $withResource = Str::replaceLast('Collection', 'Resource', $collectionClass);

            if (class_exists($withResource) && is_subclass_of($withResource, JsonResource::class)) {
                return $withResource;
            }
        }

        return null;
    }

    /**
     * @param class-string<ResourceCollection> $collectionClass
     */
    private function getWrapKey(string $collectionClass): string|null
    {
        $reflection = new \ReflectionClass($collectionClass);
        $wrapProperty = $reflection->getProperty('wrap');

        /** @var string|null $wrap */
        $wrap = $wrapProperty->getValue();

        return $wrap;
    }
}
