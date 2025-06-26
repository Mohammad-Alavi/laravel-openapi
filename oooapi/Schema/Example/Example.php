<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Example;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Example\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Example\Fields\ExternalValue;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Example\Fields\Summary;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use Webmozart\Assert\Assert;

final class Example extends ExtensibleObject
{
    protected Summary|null $summary = null;
    protected Description|null $description = null;
    protected mixed $value = null;
    protected ExternalValue|null $externalValue = null;
    private readonly string $key;

    final public static function create(string|null $key = null): self
    {
        $static = new self();

        $static->key = $key ?? '';

        return $static;
    }

    final public function key(): string
    {
        return $this->key;
    }

    public function summary(Summary $summary): self
    {
        $clone = clone $this;

        $clone->summary = $summary;

        return $clone;
    }

    public function description(Description $description): self
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function value(mixed $value): self
    {
        Assert::null(
            $this->externalValue,
            'value and externalValue fields are mutually exclusive.',
        );

        $clone = clone $this;

        $clone->value = $value;

        return $clone;
    }

    public function externalValue(ExternalValue|null $externalValue): self
    {
        Assert::null(
            $this->value,
            'externalValue and value fields are mutually exclusive.',
        );

        $clone = clone $this;

        $clone->externalValue = $externalValue;

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'summary' => $this->summary,
            'description' => $this->description,
            'value' => $this->value,
            'externalValue' => $this->externalValue,
        ]);
    }
}
