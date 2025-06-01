<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\v31;

use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\FluentBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\ArrayBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\BooleanBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\ConstantBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\EnumBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\IntegerBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\NullBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\NumberBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\ObjectBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\StringBuilder;

final class FluentSchemaBuilder extends SchemaBuilder implements FluentBuilder
{
    public static function create(): FluentBuilder
    {
        return new self();
    }

    public function null(): NullBuilder
    {
        return $this->type(Type::null());
    }

    public function boolean(): BooleanBuilder
    {
        return $this->type(Type::boolean());
    }

    public function string(): StringBuilder
    {
        return $this->type(Type::string());
    }

    public function integer(): IntegerBuilder
    {
        return $this->type(Type::integer());
    }

    public function number(): NumberBuilder
    {
        return $this->type(Type::number());
    }

    public function object(): ObjectBuilder
    {
        return $this->type(Type::object());
    }

    public function array(): ArrayBuilder
    {
        return $this->type(Type::array());
    }

    public function constant(mixed $value): ConstantBuilder
    {
        return $this->const($value);
    }

    public function enumerator(mixed ...$value): EnumBuilder
    {
        return $this->enum(...$value);
    }

    public function jsonSerialize(): array
    {
        return [
            ...parent::jsonSerialize(),
        ];
    }
}
