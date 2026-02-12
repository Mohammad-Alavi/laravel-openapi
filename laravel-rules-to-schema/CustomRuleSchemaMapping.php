<?php

namespace MohammadAlavi\LaravelRulesToSchema;

use MohammadAlavi\LaravelRulesToSchema\Contracts\HasJsonSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class CustomRuleSchemaMapping
{
    /**
     * @param class-string|null $schemaProviderClass
     * @param list<mixed> $multipleTypes
     */
    private function __construct(
        private string|null $schemaProviderClass,
        private string|null $singleType,
        private array $multipleTypes,
    ) {
    }

    /** @param class-string $class */
    public static function schemaProvider(string $class): self
    {
        return new self($class, null, []);
    }

    public static function type(string $type): self
    {
        return new self(null, $type, []);
    }

    /** @param list<mixed> $types */
    public static function types(array $types): self
    {
        return new self(null, null, $types);
    }

    public static function from(mixed $value): self
    {
        if (is_array($value)) {
            return self::types($value);
        }

        if (is_string($value) && class_exists($value)) {
            return self::schemaProvider($value);
        }

        if (is_string($value)) {
            return self::type($value);
        }

        throw new \InvalidArgumentException(
            'Custom rule schema mapping must be a class-string, type string, or array of types. Got: ' . get_debug_type($value),
        );
    }

    public function isSchemaProvider(): bool
    {
        return null !== $this->schemaProviderClass;
    }

    public function apply(string $attribute, LooseFluentDescriptor $schema): LooseFluentDescriptor
    {
        if (null !== $this->schemaProviderClass) {
            $instance = app($this->schemaProviderClass);

            if (!$instance instanceof HasJsonSchema) {
                throw new \RuntimeException('Custom rule schemas must implement ' . HasJsonSchema::class);
            }

            return $instance->toJsonSchema($attribute);
        }

        if (null !== $this->singleType) {
            return $schema->type($this->singleType);
        }

        $types = array_map(
            static fn (mixed $type): string => is_object($type) && method_exists($type, 'value') ? $type->value : (string) $type,
            $this->multipleTypes,
        );

        return $schema->type(...$types);
    }
}
