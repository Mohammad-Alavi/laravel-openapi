<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema;

trait InQuery
{
    private true|null $allowReserved = null;

    public function allowReserved(): self
    {
        $clone = clone $this;

        $clone->allowReserved = true;

        return $clone;
    }

    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'allowReserved' => $this->allowReserved,
        ];
    }
}
