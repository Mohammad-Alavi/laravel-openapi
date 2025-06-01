<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptors;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\TypeAware;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Vocabularies\Applicator;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Vocabularies\MetaData;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Generatable;

// TODO: Where should we put Enum and Constant?
// They can both be equally categorized as Validation and Descriptor
final class EnumDescriptor extends Generatable implements Keyword, Descriptor, TypeAware
{
    public $metaData;
    public $applicator;
    private array $values;

    // TODO: It would be cool if enums could accept Constant or/and Schema types
    public static function create(...$value): self
    {
        $instance = new self();
        $instance->values = $value;
        $instance->metaData = MetaData::create();
        $instance->applicator = Applicator::create();

        return $instance;
    }

    public function value(): array
    {
        return $this->values;
    }

    public function is(string $type): bool
    {
        return $type === self::name();
    }

    public static function name(): string
    {
        return 'enum';
    }

    protected function toArray(): array
    {
        return [
            self::name() => $this->values,
            ...$this->metaData->jsonSerialize(),
            ...$this->applicator->jsonSerialize(),
        ];
    }
}
