<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent;

use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\ArrayBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\BooleanBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\ConstantBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\EnumBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\IntegerBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\NullBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\NumberBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\ObjectBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\Fluent\Methods\StringBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\Builder\JSONSchemaBuilder;

interface FluentBuilder extends JSONSchemaBuilder, NullBuilder, BooleanBuilder, StringBuilder, IntegerBuilder, NumberBuilder, ObjectBuilder, ArrayBuilder, ConstantBuilder, EnumBuilder
{
    public function null(): NullBuilder;

    public function boolean(): BooleanBuilder;

    public function string(): StringBuilder;

    public function integer(): IntegerBuilder;

    public function number(): NumberBuilder;

    public function object(): ObjectBuilder;

    public function array(): ArrayBuilder;

    public function constant(mixed $value): ConstantBuilder;

    public function enumerator(mixed ...$value): EnumBuilder;
}
