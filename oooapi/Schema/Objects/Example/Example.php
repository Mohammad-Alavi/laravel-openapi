<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Fields\ExternalValue;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Summary;
use Webmozart\Assert\Assert;

/**
 * Example Object.
 *
 * Provides an example of a schema or media type. The value and externalValue
 * fields are mutually exclusive.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#example-object
 */
final class Example extends ExtensibleObject
{
    private Summary|null $summary = null;
    private Description|null $description = null;
    private mixed $value = null;
    private ExternalValue|null $externalValue = null;

    public function summary(string $summary): self
    {
        $clone = clone $this;

        $clone->summary = Summary::create($summary);

        return $clone;
    }

    public static function create(): self
    {
        return new self();
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

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

    public function externalValue(string $externalValue): self
    {
        Assert::null(
            $this->value,
            'externalValue and value fields are mutually exclusive.',
        );

        $clone = clone $this;

        $clone->externalValue = ExternalValue::create($externalValue);

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'summary' => $this->summary,
            'description' => $this->description,
            'value' => $this->value,
            'externalValue' => $this->externalValue,
        ]);
    }
}
