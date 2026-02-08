<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\HeaderParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\SerializationRule;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\Examples;
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
 * @see https://spec.openapis.org/oas/v3.2.0#header-object
 */
final class Header extends ExtensibleObject
{
    private Description|null $description = null;
    private true|null $required = null;
    private true|null $deprecated = null;

    /** @var mixed Example of the header; mutually exclusive with examples */
    private mixed $example = null;

    private Examples|null $examples = null;

    private function __construct(
        private readonly SerializationRule|null $serializationRule = null,
    ) {
    }

    public static function create(Content|HeaderParameter|null $serializationRule = null): self
    {
        return new self($serializationRule);
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
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
     * Example of the header's potential value.
     *
     * The example SHOULD match the specified schema if one is present.
     * The example field is mutually exclusive of the examples field.
     */
    public function example(mixed $example): self
    {
        Assert::null(
            $this->examples,
            'example and examples fields are mutually exclusive. '
            . 'See: https://spec.openapis.org/oas/v3.2.0#header-object',
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
            'examples and example fields are mutually exclusive. '
            . 'See: https://spec.openapis.org/oas/v3.2.0#header-object',
        );

        $clone = clone $this;

        $clone->examples = Examples::create(...$exampleEntry);

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'description' => $this->description,
            'required' => $this->required,
            'deprecated' => $this->deprecated,
            ...$this->mergeFields($this->serializationRule),
            'example' => $this->example,
            'examples' => $this->examples,
        ]);
    }
}
