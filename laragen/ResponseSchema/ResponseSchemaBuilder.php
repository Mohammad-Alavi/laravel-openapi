<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema;

use Illuminate\Http\Resources\Json\JsonResource;
use MohammadAlavi\Laragen\ModelSchema\ModelSchemaInferrer;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Compilable;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use Webmozart\Assert\Assert;

final readonly class ResponseSchemaBuilder
{
    public function __construct(
        private JsonResourceAnalyzer $analyzer,
        private ModelSchemaInferrer $modelSchemaInferrer,
        private ResourceModelDetector $modelDetector,
    ) {
    }

    /**
     * Build a JSON Schema from a JsonResource class.
     *
     * @param class-string<JsonResource> $resourceClass
     */
    public function build(string $resourceClass): JSONSchema
    {
        $fields = $this->analyzer->analyze($resourceClass);
        $wrapKey = $this->analyzer->getWrapKey($resourceClass);
        $propertySchemas = $this->resolveModelPropertySchemas($resourceClass);

        $innerSchema = $this->buildFieldsSchema($fields, $propertySchemas);

        if (null === $wrapKey) {
            return $innerSchema;
        }

        return Schema::object()->properties(
            Property::create($wrapKey, $innerSchema),
        );
    }

    /**
     * @param class-string<JsonResource> $resourceClass
     *
     * @return array<string, array<string, mixed>>
     */
    private function resolveModelPropertySchemas(string $resourceClass): array
    {
        $modelClass = $this->modelDetector->detect($resourceClass);

        if (null === $modelClass) {
            return [];
        }

        $schema = $this->modelSchemaInferrer->infer($modelClass);
        Assert::isInstanceOf($schema, Compilable::class);

        /** @var array<string, mixed> $compiled */
        $compiled = $schema->compile();

        /** @var array<string, array<string, mixed>> $properties */
        $properties = $compiled['properties'] ?? [];

        return $properties;
    }

    /**
     * @param ResourceField[] $fields
     * @param array<string, array<string, mixed>> $propertySchemas
     */
    private function buildFieldsSchema(array $fields, array $propertySchemas): JSONSchema
    {
        $properties = [];

        foreach ($fields as $field) {
            $properties[] = Property::create($field->name, $this->fieldToSchema($field, $propertySchemas));
        }

        if ([] === $properties) {
            return Schema::object();
        }

        return Schema::object()->properties(...$properties);
    }

    /**
     * @param array<string, array<string, mixed>> $propertySchemas
     */
    private function fieldToSchema(ResourceField $field, array $propertySchemas): JSONSchema
    {
        if ($field->isLiteral) {
            return Schema::enum($field->literalValue);
        }

        if ($field->isRelationship && null !== $field->resourceClass) {
            /** @var class-string<JsonResource> $resourceClass */
            $resourceClass = $field->resourceClass;
            $nestedFields = $this->analyzer->analyze($resourceClass);
            $nestedPropertySchemas = $this->resolveModelPropertySchemas($resourceClass);

            return $this->buildFieldsSchema($nestedFields, $nestedPropertySchemas);
        }

        if ($field->isModelProperty && isset($propertySchemas[$field->modelProperty])) {
            return Schema::from($propertySchemas[$field->modelProperty]);
        }

        // Model properties and conditional/unknown fields default to string
        return Schema::string();
    }
}
