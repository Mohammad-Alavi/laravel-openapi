<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Fields\Kind;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Summary;

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
    private Summary|null $summary = null;
    private Description|null $description = null;
    private ExternalDocumentation|null $externalDocumentation = null;

    /** @var Name|null Parent tag name for hierarchical nesting */
    private Name|null $parent = null;

    /** @var Kind|string|null Tag classification */
    private Kind|string|null $kind = null;

    private function __construct(
        private readonly Name $name,
    ) {
    }

    public static function create(
        string $name,
    ): self {
        return new self(Name::create($name));
    }

    /**
     * A short description for display purposes.
     *
     * Used when displaying lists of tags in navigation or documentation.
     */
    public function summary(string $summary): self
    {
        $clone = clone $this;

        $clone->summary = Summary::create($summary);

        return $clone;
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

    /**
     * The name of a parent tag for hierarchical nesting.
     *
     * The named tag MUST exist in the API description.
     * Circular references between parent and child tags MUST NOT be used.
     *
     * @see OpenAPI::validateTagHierarchy() for document-level validation
     */
    public function parent(string $parentTagName): self
    {
        $clone = clone $this;

        $clone->parent = Name::create($parentTagName);

        return $clone;
    }

    /**
     * A machine-readable string to categorize what sort of tag it is.
     *
     * Common values are 'nav' (navigation), 'badge' (visible badge),
     * and 'audience' (API consumer group). Any string value can be used.
     */
    public function kind(Kind|string $kind): self
    {
        $clone = clone $this;

        $clone->kind = $kind;

        return $clone;
    }

    public function name(): string
    {
        return $this->name->value();
    }

    public function parentName(): string|null
    {
        return $this->parent?->value();
    }

    public function toArray(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'summary' => $this->summary,
            'description' => $this->description,
            'externalDocs' => $this->externalDocumentation,
            'parent' => $this->parent,
            'kind' => $this->kind instanceof Kind ? $this->kind->value : $this->kind,
        ]);
    }
}
