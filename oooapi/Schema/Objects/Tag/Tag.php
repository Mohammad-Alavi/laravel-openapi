<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;

/**
 * Tag Object.
 *
 * Adds metadata to a single tag that is used by the Operation Object.
 * The name field is required and must be unique among all tags.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#tag-object
 */
final class Tag extends ExtensibleObject
{
    private Description|null $description = null;
    private ExternalDocumentation|null $externalDocumentation = null;

    private function __construct(
        private readonly Name $name,
    ) {
    }

    public static function create(
        string $name,
    ): self {
        return new self(Name::create($name));
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public function externalDocs(ExternalDocumentation $externalDocumentation): self
    {
        $clone = clone $this;

        $clone->externalDocumentation = $externalDocumentation;

        return $clone;
    }

    public function name(): string
    {
        return $this->name->value();
    }

    public function toArray(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'description' => $this->description,
            'externalDocs' => $this->externalDocumentation,
        ]);
    }
}
