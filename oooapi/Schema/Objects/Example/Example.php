<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Fields\ExternalValue;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Summary;
use Webmozart\Assert\Assert;

final class Example extends ExtensibleObject
{
    private Summary|null $summary = null;
    private Description|null $description = null;
    private mixed $value = null;
    private ExternalValue|null $externalValue = null;

    public static function create(): self
    {
        return new self();
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
