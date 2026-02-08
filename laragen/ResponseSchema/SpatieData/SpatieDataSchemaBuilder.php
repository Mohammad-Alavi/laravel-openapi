<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema\SpatieData;

use MohammadAlavi\Laragen\ResponseSchema\ResponseSchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Compilable;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final readonly class SpatieDataSchemaBuilder implements ResponseSchemaBuilder
{
    /** @var string[] */
    private const DATE_TIME_CLASSES = [
        \DateTime::class,
        \DateTimeImmutable::class,
        'Carbon\Carbon',
        'Carbon\CarbonImmutable',
    ];

    public function build(string $responseClass): JSONSchema
    {
        /** @var class-string<Data> $responseClass */
        $reflection = new \ReflectionClass($responseClass);
        $constructor = $reflection->getConstructor();

        if (null === $constructor) {
            return Schema::object();
        }

        $properties = [];
        $required = [];

        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            $propertySchema = $this->parameterToSchema($param);

            $properties[] = Property::create($name, $propertySchema);

            if ($this->isRequired($param)) {
                $required[] = $name;
            }
        }

        if ([] === $properties) {
            return Schema::object();
        }

        $schema = Schema::object()->properties(...$properties);

        if ([] !== $required) {
            $schema = $schema->required(...$required);
        }

        return $schema;
    }

    private function parameterToSchema(\ReflectionParameter $param): JSONSchema
    {
        $type = $param->getType();

        if (null === $type) {
            return Schema::string();
        }

        if ($type instanceof \ReflectionNamedType) {
            return $this->namedTypeToSchema($type, $param);
        }

        if ($type instanceof \ReflectionUnionType) {
            return $this->unionTypeToSchema($type, $param);
        }

        return Schema::string();
    }

    private function namedTypeToSchema(\ReflectionNamedType $type, \ReflectionParameter $param): JSONSchema
    {
        $schema = $this->resolveNamedType($type, $param);

        if ($type->allowsNull() && !$type->isBuiltin()) {
            return $this->makeNullable($schema);
        }

        if ($type->allowsNull() && $type->isBuiltin()) {
            return $this->makeNullable($schema);
        }

        return $schema;
    }

    private function resolveNamedType(\ReflectionNamedType $type, \ReflectionParameter $param): JSONSchema
    {
        $typeName = $type->getName();

        if ($type->isBuiltin()) {
            return $this->builtinTypeToSchema($typeName, $param);
        }

        return $this->classTypeToSchema($typeName, $param);
    }

    private function builtinTypeToSchema(string $typeName, \ReflectionParameter $param): JSONSchema
    {
        return match ($typeName) {
            'string' => Schema::string(),
            'int' => Schema::integer(),
            'float' => Schema::number(),
            'bool' => Schema::boolean(),
            'array' => $this->resolveArraySchema($param),
            default => Schema::string(),
        };
    }

    private function classTypeToSchema(string $className, \ReflectionParameter $param): JSONSchema
    {
        if (is_subclass_of($className, Data::class)) {
            return $this->build($className);
        }

        if (enum_exists($className) && is_subclass_of($className, \BackedEnum::class)) {
            /** @var \BackedEnum[] $cases */
            $cases = $className::cases();

            return Schema::enum(...array_map(
                static fn (\BackedEnum $case): string|int => $case->value,
                $cases,
            ));
        }

        if ($this->isDateTimeClass($className)) {
            return Schema::string()->format(StringFormat::DATE_TIME);
        }

        return Schema::string();
    }

    private function unionTypeToSchema(\ReflectionUnionType $type, \ReflectionParameter $param): JSONSchema
    {
        $nonOptionalTypes = [];

        foreach ($type->getTypes() as $innerType) {
            if ($innerType instanceof \ReflectionNamedType && Optional::class === $innerType->getName()) {
                continue;
            }

            if ($innerType instanceof \ReflectionNamedType && 'null' === $innerType->getName()) {
                continue;
            }

            $nonOptionalTypes[] = $innerType;
        }

        if ([] === $nonOptionalTypes) {
            return Schema::string();
        }

        $primaryType = $nonOptionalTypes[0];

        if (!$primaryType instanceof \ReflectionNamedType) {
            return Schema::string();
        }

        $schema = $this->resolveNamedType($primaryType, $param);

        if ($this->unionContainsNull($type)) {
            return $this->makeNullable($schema);
        }

        return $schema;
    }

    private function resolveArraySchema(\ReflectionParameter $param): JSONSchema
    {
        // DataCollectionOf targets properties, not parameters.
        // For promoted constructor params, read from the class property.
        $declaringFunction = $param->getDeclaringFunction();

        if ($declaringFunction instanceof \ReflectionMethod) {
            $declaringClass = $declaringFunction->getDeclaringClass();

            if ($declaringClass->hasProperty($param->getName())) {
                $property = $declaringClass->getProperty($param->getName());
                $attributes = $property->getAttributes(DataCollectionOf::class);

                if ([] !== $attributes) {
                    /** @var DataCollectionOf $dataCollectionOf */
                    $dataCollectionOf = $attributes[0]->newInstance();

                    return Schema::array()->items($this->build($dataCollectionOf->class));
                }
            }
        }

        return Schema::array();
    }

    private function isRequired(\ReflectionParameter $param): bool
    {
        if ($param->isDefaultValueAvailable()) {
            return false;
        }

        $type = $param->getType();

        if ($type instanceof \ReflectionNamedType && $type->allowsNull()) {
            return false;
        }

        if ($type instanceof \ReflectionUnionType) {
            foreach ($type->getTypes() as $innerType) {
                if ($innerType instanceof \ReflectionNamedType && Optional::class === $innerType->getName()) {
                    return false;
                }

                if ($innerType instanceof \ReflectionNamedType && 'null' === $innerType->getName()) {
                    return false;
                }
            }
        }

        return true;
    }

    private function isDateTimeClass(string $className): bool
    {
        foreach (self::DATE_TIME_CLASSES as $dateTimeClass) {
            if ($className === $dateTimeClass || is_subclass_of($className, $dateTimeClass)) {
                return true;
            }
        }

        return false;
    }

    private function makeNullable(JSONSchema $schema): JSONSchema
    {
        \Webmozart\Assert\Assert::isInstanceOf($schema, Compilable::class);

        /** @var array<string, mixed> $compiled */
        $compiled = $schema->compile();

        return LooseFluentDescriptor::from([
            'anyOf' => [
                $compiled,
                ['type' => 'null'],
            ],
        ]);
    }

    private function unionContainsNull(\ReflectionUnionType $type): bool
    {
        foreach ($type->getTypes() as $innerType) {
            if ($innerType instanceof \ReflectionNamedType && 'null' === $innerType->getName()) {
                return true;
            }
        }

        return false;
    }
}
