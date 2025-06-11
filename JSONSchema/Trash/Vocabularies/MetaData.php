<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\Vocabularies;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DefaultValue;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Deprecated;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Description;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Examples;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\IsReadOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\IsWriteOnly;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Title;

final class MetaData extends Generatable
{
    private Title|null $title = null;
    private Description|null $description = null;
    private DefaultValue|null $defaultValue = null;
    private Deprecated|null $deprecated = null;
    private Examples|null $examples = null;
    private IsReadOnly|null $isReadOnly = null;
    private IsWriteOnly|null $isWriteOnly = null;

    private function __construct()
    {
    }

    public function title(string $value): self
    {
        $clone = clone $this;

        $clone->title = Title::create($value);

        return $clone;
    }

    public static function create(): self
    {
        return new self();
    }

    public function description(string $value): self
    {
        $clone = clone $this;

        $clone->description = Description::create($value);

        return $clone;
    }

    public function default(mixed $value): self
    {
        $clone = clone $this;

        $clone->defaultValue = DefaultValue::create($value);

        return $clone;
    }

    public function deprecated(bool $value): self
    {
        $clone = clone $this;

        $clone->deprecated = Deprecated::create($value);

        return $clone;
    }

    public function examples(mixed ...$value): self
    {
        $clone = clone $this;

        $clone->examples = Examples::create(...$value);

        return $clone;
    }

    public function readOnly(bool $value): self
    {
        $clone = clone $this;

        $clone->isReadOnly = IsReadOnly::create($value);

        return $clone;
    }

    public function writeOnly(bool $value): self
    {
        $clone = clone $this;

        $clone->isWriteOnly = IsWriteOnly::create($value);

        return $clone;
    }

    protected function toArray(): array
    {
        $metaData = [];
        if ($this->title instanceof Title) {
            $metaData[Title::name()] = $this->title->value();
        }
        if ($this->description instanceof Description) {
            $metaData[Description::name()] = $this->description->value();
        }
        if ($this->defaultValue instanceof DefaultValue) {
            $metaData[DefaultValue::name()] = $this->defaultValue->value();
        }
        if ($this->deprecated instanceof Deprecated) {
            $metaData[Deprecated::name()] = $this->deprecated->value();
        }
        if ($this->examples instanceof Examples) {
            $metaData[Examples::name()] = $this->examples->value();
        }
        if ($this->isReadOnly instanceof IsReadOnly) {
            $metaData[IsReadOnly::name()] = $this->isReadOnly->value();
        }
        if ($this->isWriteOnly instanceof IsWriteOnly) {
            $metaData[IsWriteOnly::name()] = $this->isWriteOnly->value();
        }

        return Arr::filter($metaData);
    }
}
