<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema;

use Illuminate\Http\Resources\Json\JsonResource;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

final readonly class ResponseSchemaBuilder
{
    public function __construct(
        private JsonResourceAnalyzer $analyzer,
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

        $innerSchema = $this->buildFieldsSchema($fields);

        if (null === $wrapKey) {
            return $innerSchema;
        }

        return Schema::object()->properties(
            Property::create($wrapKey, $innerSchema),
        );
    }

    /**
     * @param ResourceField[] $fields
     */
    private function buildFieldsSchema(array $fields): JSONSchema
    {
        $properties = [];

        foreach ($fields as $field) {
            $properties[] = Property::create($field->name, $this->fieldToSchema($field));
        }

        if ([] === $properties) {
            return Schema::object();
        }

        return Schema::object()->properties(...$properties);
    }

    private function fieldToSchema(ResourceField $field): JSONSchema
    {
        if ($field->isLiteral) {
            return Schema::enum($field->literalValue);
        }

        if ($field->isRelationship && null !== $field->resourceClass) {
            /** @var class-string<JsonResource> $resourceClass */
            $resourceClass = $field->resourceClass;
            $nestedFields = $this->analyzer->analyze($resourceClass);

            return $this->buildFieldsSchema($nestedFields);
        }

        // Model properties and conditional/unknown fields default to string
        return Schema::string();
    }
}
