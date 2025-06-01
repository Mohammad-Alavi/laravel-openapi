<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptors;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\HasTypeTrait;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\TypeAware;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Vocabularies\Applicator;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Vocabularies\MetaData;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

final class BooleanDescriptor extends ExtensibleObject implements Descriptor, TypeAware
{
    use HasTypeTrait;

    public static function create(): self
    {
        $instance = new self();
        $instance->type = Type::boolean();
        $instance->metaData = MetaData::create();
        $instance->applicator = Applicator::create();

        return $instance;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            $this->type::name() => $this->type->value(),
            ...$this->metaData->jsonSerialize(),
            ...$this->applicator->jsonSerialize(),
        ]);
    }
}
