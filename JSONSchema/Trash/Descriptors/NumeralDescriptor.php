<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptors;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ExclusiveMaximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\ExclusiveMinimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Maximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Minimum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MultipleOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\HasTypeTrait;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\TypeAware;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Vocabularies\Applicator;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Vocabularies\MetaData;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

abstract class NumeralDescriptor extends ExtensibleObject implements Descriptor, TypeAware
{
    use HasTypeTrait;

    private ExclusiveMaximum|null $exclusiveMaximum = null;
    private ExclusiveMinimum|null $exclusiveMinimum = null;
    private Maximum|null $maximum = null;
    private Minimum|null $minimum = null;
    private MultipleOf|null $multipleOf = null;

    /**
     * Integer is a data type specific to OAS and is not included in the JSON Schema specification.
     */
    final protected static function integer(): static
    {
        $static = new static();
        $static->type = Type::integer();
        $static->metaData = MetaData::create();
        $static->applicator = Applicator::create();

        return $static;
    }

    final protected static function number(): static
    {
        $static = new static();
        $static->type = Type::number();

        return $static;
    }

    /** A number is valid if it is strictly less than (not equal to) the provided maximum value. */
    final public function exclusiveMaximum(float $value): self
    {
        $clone = clone $this;

        $clone->exclusiveMaximum = ExclusiveMaximum::create($value);

        return $clone;
    }

    /** A number is valid if it is strictly greater than (not equal to) the provided minimum value. */
    final public function exclusiveMinimum(float $value): self
    {
        $clone = clone $this;

        $clone->exclusiveMinimum = ExclusiveMinimum::create($value);

        return $clone;
    }

    /** A number is valid if it is less than, or equal to this value */
    final public function maximum(float $value): self
    {
        $clone = clone $this;

        $clone->maximum = Maximum::create($value);

        return $clone;
    }

    /** A number is valid if it is greater than, or equal to this value */
    final public function minimum(float $value): self
    {
        $clone = clone $this;

        $clone->minimum = Minimum::create($value);

        return $clone;
    }

    /** A number is valid if it is divisible by this value */
    final public function multipleOf(float $value): self
    {
        $clone = clone $this;

        $clone->multipleOf = MultipleOf::create($value);

        return $clone;
    }

    final protected function toArray(): array
    {
        $assertions = [];
        if ($this->format) {
            $assertions[$this->format::keyword()] = $this->format->value();
        }
        if ($this->exclusiveMaximum instanceof ExclusiveMaximum) {
            $assertions[$this->exclusiveMaximum::name()] = $this->exclusiveMaximum->value();
        }
        if ($this->exclusiveMinimum instanceof ExclusiveMinimum) {
            $assertions[$this->exclusiveMinimum::name()] = $this->exclusiveMinimum->value();
        }
        if ($this->maximum instanceof Maximum) {
            $assertions[$this->maximum::name()] = $this->maximum->value();
        }
        if ($this->minimum instanceof Minimum) {
            $assertions[$this->minimum::name()] = $this->minimum->value();
        }
        if ($this->multipleOf instanceof MultipleOf) {
            $assertions[$this->multipleOf::name()] = $this->multipleOf->value();
        }

        return Arr::filter([
            $this->type::name() => $this->type->value(),
            ...$assertions,
            ...$this->metaData->jsonSerialize(),
            ...$this->applicator->jsonSerialize(),
        ]);
    }
}
