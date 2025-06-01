<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptors;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Items;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MaxItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinContains;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\MinItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\UniqueItems;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\HasTypeTrait;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\TypeAware;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Vocabularies\Applicator;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Vocabularies\MetaData;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

final class ArrayDescriptor extends ExtensibleObject implements Descriptor, TypeAware
{
    use HasTypeTrait;

    private MaxItems|null $maxItems = null;
    private MinItems|null $minItems = null;
    private MaxContains|null $maxContains = null;
    private MinContains|null $minContains = null;
    private UniqueItems|null $uniqueItems = null;

    // APPLICATORS
    private Items|null $items = null;

    public function maxItems(int $value): self
    {
        $clone = clone $this;

        $clone->maxItems = MaxItems::create($value);

        return $clone;
    }

    public static function create(): self
    {
        $instance = new self();
        $instance->type = Type::array();
        $instance->metaData = MetaData::create();
        $instance->applicator = Applicator::create();

        return $instance;
    }

    public function minItems(int $value): self
    {
        $clone = clone $this;

        $clone->minItems = MinItems::create($value);

        return $clone;
    }

    public function maxContains(int $value): self
    {
        $clone = clone $this;

        $clone->maxContains = MaxContains::create($value);

        return $clone;
    }

    public function minContains(int $value): self
    {
        $clone = clone $this;

        $clone->minContains = MinContains::create($value);

        return $clone;
    }

    public function uniqueItems(bool $value = true): self
    {
        $clone = clone $this;

        $clone->uniqueItems = UniqueItems::create($value);

        return $clone;
    }

    public function items(Descriptor $descriptor): self
    {
        $clone = clone $this;

        $clone->items = Items::create($descriptor);

        return $clone;
    }

    protected function toArray(): array
    {
        $assertions = [];
        if ($this->maxItems instanceof MaxItems) {
            $assertions[$this->maxItems::name()] = $this->maxItems->value();
        }
        if ($this->minItems instanceof MinItems) {
            $assertions[$this->minItems::name()] = $this->minItems->value();
        }
        if ($this->maxContains instanceof MaxContains) {
            $assertions[$this->maxContains::name()] = $this->maxContains->value();
        }
        if ($this->minContains instanceof MinContains) {
            $assertions[$this->minContains::name()] = $this->minContains->value();
        }
        if ($this->uniqueItems instanceof UniqueItems) {
            $assertions[$this->uniqueItems::name()] = $this->uniqueItems->value();
        }

        $applicators = [];
        if ($this->items instanceof Items) {
            $applicators[$this->items::name()] = $this->items->value();
        }

        return Arr::filter([
            $this->type::name() => $this->type->value(),
            ...$assertions,
            ...$applicators,
            ...$this->metaData->jsonSerialize(),
            ...$this->applicator->jsonSerialize(),
        ]);
    }
}
