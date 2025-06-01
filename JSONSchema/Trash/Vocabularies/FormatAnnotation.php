<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Trash\Vocabularies;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Format;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Generatable;

final class FormatAnnotation extends Generatable
{
    private Format|null $format = null;

    private function __construct()
    {
    }

    public function format(DefinedFormat $definedFormat): self
    {
        $clone = clone $this;

        $clone->format = Format::create($definedFormat);

        return $clone;
    }

    public static function create(): self
    {
        return new self();
    }

    protected function toArray(): array
    {
        $formatAnnotations = [];
        if ($this->format instanceof Format) {
            $formatAnnotations[Format::name()] = $this->format->value();
        }

        return Arr::filter($formatAnnotations);
    }
}
