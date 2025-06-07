<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\DefaultValue;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields\Enum;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;
use Webmozart\Assert\Assert;

final class ServerVariable extends ExtensibleObject
{
    private Enum|null $enum = null;
    private Description|null $description = null;

    private function __construct(
        private DefaultValue $defaultValue,
    ) {
    }

    public static function create(DefaultValue $defaultValue): self
    {
        return new self($defaultValue);
    }

    public function default(DefaultValue $defaultValue): self
    {
        $clone = clone $this;

        $clone->defaultValue = $defaultValue;

        return $clone;
    }

    public function enum(Enum|null $enum): self
    {
        Assert::true(
            is_null($enum) || in_array($this->defaultValue->value(), $enum->values(), true),
            'The default value must exist in the enum’s values.',
        );

        $clone = clone $this;

        $clone->enum = $enum;

        return $clone;
    }

    public function description(Description|null $description): self
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'enum' => $this->enum,
            'default' => $this->defaultValue,
            'description' => $this->description,
        ]);
    }
}
