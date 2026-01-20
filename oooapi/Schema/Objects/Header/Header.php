<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\Examples;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles\Simple;
use Webmozart\Assert\Assert;

/**
 * Header Object.
 *
 * Describes a single header parameter. The Header Object follows the
 * structure of the Parameter Object with the following changes: name
 * MUST NOT be specified, it is given in the corresponding headers map.
 *
 * Headers only support the 'simple' style per OpenAPI specification.
 *
 * @see https://spec.openapis.org/oas/v3.1.1#header-object
 */
final class Header extends ExtensibleObject
{
    private Description|null $description = null;
    private true|null $required = null;
    private true|null $deprecated = null;
    private Simple|null $style = null;
    private JSONSchema|null $schema = null;

    /** @var mixed Example of the header; mutually exclusive with examples */
    private mixed $example = null;

    private Examples|null $examples = null;
    private Content|null $content = null;

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public static function create(): self
    {
        return new self();
    }

    public function required(): self
    {
        $clone = clone $this;

        $clone->required = true;

        return $clone;
    }

    public function deprecated(): self
    {
        $clone = clone $this;

        $clone->deprecated = true;

        return $clone;
    }

    /**
     * Set the serialization style for this header.
     *
     * Headers only support the 'simple' style per OpenAPI specification.
     * Use Simple::create() optionally with ->explode() for array serialization.
     *
     * Example: ->style(Simple::create()->explode())
     */
    public function style(Simple $style): self
    {
        $clone = clone $this;

        $clone->style = $style;

        return $clone;
    }

    public function schema(JSONSchema $schema): self
    {
        $clone = clone $this;

        $clone->schema = $schema;

        return $clone;
    }

    /**
     * Example of the header's potential value.
     *
     * The example SHOULD match the specified schema if one is present.
     * The example field is mutually exclusive of the examples field.
     */
    public function example(mixed $example): self
    {
        Assert::null(
            $this->examples,
            'example and examples fields are mutually exclusive.',
        );

        $clone = clone $this;

        $clone->example = $example;

        return $clone;
    }

    /**
     * Examples of the header's potential values.
     *
     * Each example SHOULD match the specified schema if one is present.
     * The examples field is mutually exclusive of the example field.
     */
    public function examples(ExampleEntry ...$exampleEntry): self
    {
        Assert::null(
            $this->example,
            'examples and example fields are mutually exclusive.',
        );

        $clone = clone $this;

        $clone->examples = Examples::create(...$exampleEntry);

        return $clone;
    }

    public function content(ContentEntry ...$contentEntry): self
    {
        $clone = clone $this;

        $clone->content = Content::create(...$contentEntry);

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'description' => $this->description,
            'required' => $this->required,
            'deprecated' => $this->deprecated,
            ...$this->mergeFields($this->style),
            'schema' => $this->schema,
            'example' => $this->example,
            'examples' => $this->examples,
            'content' => $this->content,
        ]);
    }
}
