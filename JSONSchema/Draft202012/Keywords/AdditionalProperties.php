<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

final readonly class AdditionalProperties implements Keyword
{
    private function __construct(
        private Descriptor|bool $schema,
    ) {
    }

    public static function create(Descriptor|bool $schema): self
    {
        return new self($schema);
    }

    public static function name(): string
    {
        return 'additionalProperties';
    }

    public function value(): Descriptor|bool
    {
        return $this->schema;
    }
}
