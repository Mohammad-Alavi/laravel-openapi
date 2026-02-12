<?php

namespace MohammadAlavi\Laragen\RequestSchema\Annotation;

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\Annotations\DetectedBodyParam;
use MohammadAlavi\Laragen\RequestSchema\ContentEncoding;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaBuilder;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResult;
use MohammadAlavi\Laragen\RequestSchema\RequestTarget;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use Webmozart\Assert\Assert;

final readonly class AnnotationBodyParamSchemaBuilder implements RequestSchemaBuilder
{
    public function build(mixed $detected, Route $route): RequestSchemaResult
    {
        Assert::isArray($detected);
        Assert::allIsInstanceOf($detected, DetectedBodyParam::class);

        $properties = [];
        $required = [];

        /** @var DetectedBodyParam $param */
        foreach ($detected as $param) {
            $properties[] = Property::create($param->name, self::typeToSchema($param->type));

            if ($param->required) {
                $required[] = $param->name;
            }
        }

        $schema = Schema::object()->properties(...$properties);

        if ([] !== $required) {
            $schema = $schema->required(...$required);
        }

        return new RequestSchemaResult(
            schema: $schema,
            target: RequestTarget::BODY,
            encoding: ContentEncoding::JSON,
        );
    }

    private static function typeToSchema(string $type): JSONSchema
    {
        return match ($type) {
            'string' => Schema::string(),
            'integer', 'int' => Schema::integer(),
            'number' => Schema::number(),
            'boolean', 'bool' => Schema::boolean(),
            'array' => Schema::array(),
            'object' => Schema::object(),
            default => Schema::string(),
        };
    }
}
