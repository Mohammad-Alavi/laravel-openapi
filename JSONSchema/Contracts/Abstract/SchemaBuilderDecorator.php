<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Abstract;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Builder\Builder;
use MohammadAlavi\ObjectOrientedJSONSchema\Formats\DefinedFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Defs\Def;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\DependentRequired\Dependency;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Vocabulary\Vocab;
use MohammadAlavi\ObjectOrientedJSONSchema\SchemaBuilder;

abstract class SchemaBuilderDecorator implements Builder
{
    private SchemaBuilder $schemaBuilder;

    public function __construct()
    {
        $this->schemaBuilder = new SchemaBuilder();
    }

    public function schema(string $uri): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->schema($uri);

        return $clone;
    }

    public function ref(string $uri): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->ref($uri);

        return $clone;
    }

    public function defs(Def ...$def): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->defs(...$def);

        return $clone;
    }

    public function id(string $uri): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->id($uri);

        return $clone;
    }

    public function comment(string $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->comment($value);

        return $clone;
    }

    public function anchor(string $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->anchor($value);

        return $clone;
    }

    public function dynamicAnchor(string $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->dynamicAnchor($value);

        return $clone;
    }

    public function dynamicRef(string $uri): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->dynamicRef($uri);

        return $clone;
    }

    public function vocabulary(Vocab ...$vocab): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->vocabulary(...$vocab);

        return $clone;
    }

    public function unevaluatedItems(Builder $builder): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->unevaluatedItems($builder);

        return $clone;
    }

    public function unevaluatedProperties(Builder $builder): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->unevaluatedProperties($builder);

        return $clone;
    }

    public function format(DefinedFormat $definedFormat): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->format($definedFormat);

        return $clone;
    }

    public function maxLength(int $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->maxLength($value);

        return $clone;
    }

    public function minLength(int $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->minLength($value);

        return $clone;
    }

    public function pattern(string $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->pattern($value);

        return $clone;
    }

    public function type(Type|string ...$type): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->type(...$type);

        return $clone;
    }

    public function exclusiveMaximum(float $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->exclusiveMaximum($value);

        return $clone;
    }

    public function exclusiveMinimum(float $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->exclusiveMinimum($value);

        return $clone;
    }

    public function maximum(float $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->maximum($value);

        return $clone;
    }

    public function minimum(float $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->minimum($value);

        return $clone;
    }

    public function multipleOf(float $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->multipleOf($value);

        return $clone;
    }

    public function maxContains(int $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->maxContains($value);

        return $clone;
    }

    public function maxItems(int $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->maxItems($value);

        return $clone;
    }

    public function minContains(int $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->minContains($value);

        return $clone;
    }

    public function minItems(int $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->minItems($value);

        return $clone;
    }

    public function uniqueItems(bool $value = true): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->uniqueItems($value);

        return $clone;
    }

    public function items(Builder $builder): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->items($builder);

        return $clone;
    }

    public function allOf(Builder ...$builder): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->allOf(...$builder);

        return $clone;
    }

    public function anyOf(Builder ...$builder): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->anyOf(...$builder);

        return $clone;
    }

    public function oneOf(Builder ...$builder): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->oneOf(...$builder);

        return $clone;
    }

    public function additionalProperties(Builder|bool $schema): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->additionalProperties($schema);

        return $clone;
    }

    public function properties(Property ...$property): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->properties(...$property);

        return $clone;
    }

    public function dependentRequired(Dependency ...$dependency): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->dependentRequired(...$dependency);

        return $clone;
    }

    public function maxProperties(int $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->maxProperties($value);

        return $clone;
    }

    public function minProperties(int $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->minProperties($value);

        return $clone;
    }

    public function required(string ...$property): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->required(...$property);

        return $clone;
    }

    public function default(mixed $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->default($value);

        return $clone;
    }

    public function deprecated(bool $value = true): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->deprecated($value);

        return $clone;
    }

    public function description(string $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->description($value);

        return $clone;
    }

    public function examples(...$example): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->examples(...$example);

        return $clone;
    }

    public function readOnly(bool $value = true): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->readOnly($value);

        return $clone;
    }

    public function writeOnly(bool $value = true): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->writeOnly($value);

        return $clone;
    }

    public function title(string $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->title($value);

        return $clone;
    }

    public function const(mixed $value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->const($value);

        return $clone;
    }

    public function enum(...$value): static
    {
        $clone = clone $this;

        $clone->schemaBuilder = $this->schemaBuilder->enum(...$value);

        return $clone;
    }

    public function jsonSerialize(): array
    {
        return $this->schemaBuilder->jsonSerialize();
    }
}
