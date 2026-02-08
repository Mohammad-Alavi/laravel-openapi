<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema;

final readonly class ResourceField
{
    private function __construct(
        public string $name,
        public bool $isModelProperty = false,
        public string|null $modelProperty = null,
        public bool $isLiteral = false,
        public string|int|float|bool|null $literalValue = null,
        public bool $isRelationship = false,
        public string|null $resourceClass = null,
        public bool $isConditional = false,
    ) {
    }

    public static function modelProperty(string $name, string $property): self
    {
        return new self(
            name: $name,
            isModelProperty: true,
            modelProperty: $property,
        );
    }

    public static function literal(string $name, string|int|float|bool $value): self
    {
        return new self(
            name: $name,
            isLiteral: true,
            literalValue: $value,
        );
    }

    /**
     * @param class-string $resourceClass
     */
    public static function relationship(string $name, string $resourceClass): self
    {
        return new self(
            name: $name,
            isRelationship: true,
            resourceClass: $resourceClass,
        );
    }

    public static function conditional(string $name): self
    {
        return new self(
            name: $name,
            isConditional: true,
        );
    }

    public static function unknown(string $name): self
    {
        return new self(name: $name);
    }
}
