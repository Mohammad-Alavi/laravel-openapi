<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

final readonly class OneOf implements Keyword
{
    private function __construct(
        private array $schema,
    ) {
    }

    public static function create(Descriptor ...$builder): self
    {
        return new self($builder);
    }

    public static function name(): string
    {
        return 'oneOf';
    }

    /** @return Descriptor[] */
    public function value(): array
    {
        return $this->schema;
    }
}
