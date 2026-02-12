<?php

namespace MohammadAlavi\Laragen\ResponseSchema\Annotation;

use MohammadAlavi\Laragen\Annotations\DetectedResponseAnnotation;
use MohammadAlavi\Laragen\ResponseSchema\ResponseSchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use Webmozart\Assert\Assert;

final readonly class AnnotationResponseSchemaBuilder implements ResponseSchemaBuilder
{
    public function build(mixed $detected): JSONSchema
    {
        Assert::isArray($detected);
        Assert::allIsInstanceOf($detected, DetectedResponseAnnotation::class);

        $annotation = $this->selectAnnotation($detected);
        /** @var array<string, mixed> $data */
        $data = json_decode($annotation->json, true, 512, JSON_THROW_ON_ERROR);

        return $this->inferObjectSchema($data);
    }

    /**
     * @param DetectedResponseAnnotation[] $annotations
     */
    private function selectAnnotation(array $annotations): DetectedResponseAnnotation
    {
        foreach ($annotations as $annotation) {
            if ($annotation->status >= 200 && $annotation->status < 300) {
                return $annotation;
            }
        }

        return $annotations[0];
    }

    /**
     * @param array<string, mixed> $data
     */
    private function inferObjectSchema(array $data): JSONSchema
    {
        $properties = [];

        foreach ($data as $key => $value) {
            $properties[] = Property::create($key, $this->inferSchema($value));
        }

        if ([] === $properties) {
            return Schema::object();
        }

        return Schema::object()->properties(...$properties);
    }

    private function inferSchema(mixed $value): JSONSchema
    {
        if (is_string($value)) {
            return Schema::string();
        }

        if (is_int($value)) {
            return Schema::integer();
        }

        if (is_float($value)) {
            return Schema::number();
        }

        if (is_bool($value)) {
            return Schema::boolean();
        }

        if (is_array($value)) {
            return $this->inferArrayOrObjectSchema($value);
        }

        // null or unknown
        return Schema::string();
    }

    /**
     * @param array<mixed> $value
     */
    private function inferArrayOrObjectSchema(array $value): JSONSchema
    {
        if ([] === $value) {
            return Schema::array();
        }

        if (array_is_list($value)) {
            return Schema::array()->items($this->inferSchema($value[0]));
        }

        return $this->inferObjectSchema($value); // @phpstan-ignore argument.type (assoc array verified by !array_is_list)
    }
}
