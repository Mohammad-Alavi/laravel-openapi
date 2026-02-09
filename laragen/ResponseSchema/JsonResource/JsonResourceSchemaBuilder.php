<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema\JsonResource;

use Illuminate\Http\Resources\Json\JsonResource;
use MohammadAlavi\Laragen\ModelSchema\ModelSchemaInferrer;
use MohammadAlavi\Laragen\ResponseSchema\ArraySchema\ArrayField;
use MohammadAlavi\Laragen\ResponseSchema\ArraySchema\ArraySchemaAnalyzer;
use MohammadAlavi\Laragen\ResponseSchema\ResponseSchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Compilable;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use Webmozart\Assert\Assert;

final readonly class JsonResourceSchemaBuilder implements ResponseSchemaBuilder
{
    public function __construct(
        private ArraySchemaAnalyzer $analyzer,
        private ModelSchemaInferrer $modelSchemaInferrer,
        private JsonResourceModelDetector $modelDetector,
    ) {
    }

    public function build(mixed $detected): JSONSchema
    {
        Assert::string($detected);

        /** @var class-string<JsonResource> $detected */
        $resourceClass = $detected;
        $fields = $this->analyzer->analyzeMethod($resourceClass, 'toArray');
        $wrapKey = $this->getWrapKey($resourceClass);
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
     */
    private function getWrapKey(string $resourceClass): string|null
    {
        $reflection = new \ReflectionClass($resourceClass);
        $wrapProperty = $reflection->getProperty('wrap');

        /** @var string|null $wrap */
        $wrap = $wrapProperty->getValue();

        return $wrap;
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
     * @param ArrayField[] $fields
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
    private function fieldToSchema(ArrayField $field, array $propertySchemas): JSONSchema
    {
        if ($field->isLiteral) {
            return Schema::enum($field->literalValue);
        }

        if ($field->isRelationship && null !== $field->resourceClass) {
            /** @var class-string<JsonResource> $resourceClass */
            $resourceClass = $field->resourceClass;
            $nestedFields = $this->analyzer->analyzeMethod($resourceClass, 'toArray');
            $nestedPropertySchemas = $this->resolveModelPropertySchemas($resourceClass);

            return $this->buildFieldsSchema($nestedFields, $nestedPropertySchemas);
        }

        if ($field->isCollection && null !== $field->resourceClass) {
            /** @var class-string<JsonResource> $resourceClass */
            $resourceClass = $field->resourceClass;
            $nestedFields = $this->analyzer->analyzeMethod($resourceClass, 'toArray');
            $nestedPropertySchemas = $this->resolveModelPropertySchemas($resourceClass);

            return Schema::array()->items($this->buildFieldsSchema($nestedFields, $nestedPropertySchemas));
        }

        if ($field->isNestedObject) {
            return $this->buildFieldsSchema($field->children, $propertySchemas);
        }

        if ($field->isTypedExpression && null !== $field->expressionType) {
            return match ($field->expressionType) {
                'integer' => Schema::integer(),
                'number' => Schema::number(),
                'boolean' => Schema::boolean(),
                default => Schema::string(),
            };
        }

        if ($field->isModelProperty && isset($propertySchemas[$field->modelProperty])) {
            return Schema::from($propertySchemas[$field->modelProperty]);
        }

        // Model properties and conditional/unknown fields default to string
        return Schema::string();
    }
}
