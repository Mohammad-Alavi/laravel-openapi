<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\Vocabularies;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AllOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\AnyOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\OneOf;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptor;

final class Applicator extends Generatable
{
    private AllOf|null $allOf = null;
    private AnyOf|null $anyOf = null;
    private OneOf|null $oneOf = null;

    private function __construct()
    {
    }

    public function allOf(bool|Descriptor ...$schema): self
    {
        $clone = clone $this;

        $clone->allOf = AllOf::create(...$schema);

        return $clone;
    }

    public static function create(): self
    {
        return new self();
    }

    public function anyOf(bool|Descriptor ...$schema): self
    {
        $clone = clone $this;

        $clone->anyOf = AnyOf::create(...$schema);

        return $clone;
    }

    public function oneOf(bool|Descriptor ...$schema): self
    {
        $clone = clone $this;

        $clone->oneOf = OneOf::create(...$schema);

        return $clone;
    }

    protected function toArray(): array
    {
        $applicators = [];
        if ($this->allOf instanceof AllOf) {
            $applicators[AllOf::name()] = $this->allOf->value();
        }
        if ($this->anyOf instanceof AnyOf) {
            $applicators[AnyOf::name()] = $this->anyOf->value();
        }
        if ($this->oneOf instanceof OneOf) {
            $applicators[OneOf::name()] = $this->oneOf->value();
        }

        return Arr::filter($applicators);
    }
}
