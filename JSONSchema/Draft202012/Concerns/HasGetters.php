<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Concerns;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Constant;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;

trait HasGetters
{
    public function getType(): array|string|null
    {
        return $this->type?->value();
    }

    /**
     * Get the properties defined in this schema.
     *
     * @return Property[]|null
     */
    public function getProperties(): array|null
    {
        return $this->properties?->value();
    }

    public function getExamples(): array|null
    {
        return $this->examples?->value();
    }

    public function getEnum(): array|null
    {
        // Assert::null($this->type, 'Only Enum type can have enum values.');

        return $this->enum?->value();
    }

    public function getConstant(): Constant|null
    {
        return $this->constant;
    }

    public function getItems(): StrictFluentDescriptor|null
    {
        return $this->items?->value();
    }

    public function getMaxLength(): int|null
    {
        return $this->maxLength?->value();
    }

    public function getMinLength(): int|null
    {
        return $this->minLength?->value();
    }

    public function getMaximum(): float|null
    {
        return $this->maximum?->value();
    }

    public function getMinimum(): float|null
    {
        return $this->minimum?->value();
    }

    public function getFormat(): string|null
    {
        return $this->format?->value();
    }

    public function getAllOf(): array|null
    {
        return $this->allOf?->value();
    }

    public function getAnyOf(): array|null
    {
        return $this->anyOf?->value();
    }

    public function getOneOf(): array|null
    {
        return $this->oneOf?->value();
    }
}
