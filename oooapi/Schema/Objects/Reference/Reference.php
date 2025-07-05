<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\NonExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Fields\Ref;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Summary;

final class Reference extends NonExtensibleObject
{
    private Summary|null $summary = null;
    private Description|null $description = null;

    // TODO: description and summary by default SHOULD override that of the referenced component.
    // This is not possible with the current implementation.
    // This is specially importance for the Response object.
    private function __construct(
        private readonly Ref $ref,
    ) {
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public static function create(string $ref): self
    {
        return new self(Ref::create($ref));
    }

    public function summary(string $summary): self
    {
        $clone = clone $this;

        $clone->summary = Summary::create($summary);

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            '$ref' => $this->ref,
            'summary' => $this->summary,
            'description' => $this->description,
        ]);
    }
}
