<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema\FractalTransformer;

use MohammadAlavi\Laragen\ModelSchema\ModelSchemaInferrer;
use MohammadAlavi\Laragen\ResponseSchema\ArraySchema\ArrayField;
use MohammadAlavi\Laragen\ResponseSchema\ArraySchema\ArraySchemaAnalyzer;
use MohammadAlavi\Laragen\ResponseSchema\ResponseSchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Compilable;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use Webmozart\Assert\Assert;

final readonly class FractalTransformerSchemaBuilder implements ResponseSchemaBuilder
{
    public function __construct(
        private ArraySchemaAnalyzer $analyzer,
        private ModelSchemaInferrer $modelSchemaInferrer,
        private FractalTransformerModelDetector $modelDetector,
    ) {
    }

    /**
     * @param class-string $responseClass
     */
    public function build(string $responseClass): JSONSchema
    {
        $fields = $this->analyzer->analyzeMethod($responseClass, 'transform');
        $propertySchemas = $this->resolveModelPropertySchemas($responseClass);

        return $this->buildFieldsSchema($fields, $propertySchemas);
    }

    /**
     * @param class-string $transformerClass
     *
     * @return array<string, array<string, mixed>>
     */
    private function resolveModelPropertySchemas(string $transformerClass): array
    {
        $modelClass = $this->modelDetector->detect($transformerClass);

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

        return Schema::string();
    }
}
