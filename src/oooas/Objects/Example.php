<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property string|null $summary
 * @property string|null $description
 * @property mixed|null $value
 * @property string|null $externalValue
 */
class Example extends BaseObject
{
    protected string|null $summary = null;
    protected string|null $description = null;
    protected mixed $value = null;
    protected string|null $externalValue = null;

    /** @return static */
    public function summary(string|null $summary): self
    {
        $instance = clone $this;

        $instance->summary = $summary;

        return $instance;
    }

    /** @return static */
    public function description(string|null $description): self
    {
        $instance = clone $this;

        $instance->description = $description;

        return $instance;
    }

    /**
     * @param mixed|null $value
     *
     * @return static
     */
    public function value(mixed $value): self
    {
        $instance = clone $this;

        $instance->value = $value;

        return $instance;
    }

    /** @return static */
    public function externalValue(string|null $externalValue): self
    {
        $instance = clone $this;

        $instance->externalValue = $externalValue;

        return $instance;
    }

    protected function generate(): array
    {
        return Arr::filter([
            'summary' => $this->summary,
            'description' => $this->description,
            'value' => $this->value,
            'externalValue' => $this->externalValue,
        ]);
    }
}
