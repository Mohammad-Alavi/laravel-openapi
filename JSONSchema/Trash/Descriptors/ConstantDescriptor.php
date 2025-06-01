<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptors;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\TypeAware;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Vocabularies\Applicator;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Vocabularies\MetaData;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Generatable;

final class ConstantDescriptor extends Generatable implements Keyword, Descriptor, TypeAware
{
    public $metaData;
    public $applicator;
    private mixed $value;

    // TODO: It would be cool if constants could accept Schema types
    public static function create(mixed $value): self
    {
        $instance = new self();
        $instance->value = $value;
        $instance->metaData = MetaData::create();
        $instance->applicator = Applicator::create();

        return $instance;
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function is(string $type): bool
    {
        return $type === self::name();
    }

    public static function name(): string
    {
        return 'const';
    }

    protected function toArray(): array
    {
        return [
            self::name() => $this->value,
            ...$this->metaData->jsonSerialize(),
            ...$this->applicator->jsonSerialize(),
        ];
    }
}
