<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\NonExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Fields\Ref;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Fields\Summary;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

final class Reference extends NonExtensibleObject
{
    // TODO: description and summary by default SHOULD override that of the referenced component.
    // This is not possible with the current implementation.
    // This is specially importance for the Response object.
    private function __construct(
        private readonly Ref $ref,
        private readonly Summary|null $summary,
        private readonly Description|null $description,
    ) {
    }

    public static function create(Ref $ref, Summary|null $summary = null, Description|null $description = null): self
    {
        return new self($ref, $summary, $description);
    }

    protected function toArray(): array
    {
        return Arr::filter([
            '$ref' => $this->ref,
            'summary' => $this->summary,
            'description' => $this->description,
        ]);
    }
}
