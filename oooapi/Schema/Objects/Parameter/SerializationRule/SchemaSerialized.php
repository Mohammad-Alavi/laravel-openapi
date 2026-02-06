<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\Examples;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Style;
use Webmozart\Assert\Assert;

/**
 * Schema-based serialization for parameters.
 *
 * Provides the schema and style-based serialization fields for parameters.
 * Supports both singular `example` and plural `examples` fields, which are
 * mutually exclusive per OAS 3.2.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#parameter-object
 */
abstract class SchemaSerialized implements SerializationRule
{
    private Examples|null $examples = null;

    /** @var mixed Example of the parameter's potential value */
    private mixed $example = null;

    final protected function __construct(
        private readonly JSONSchema|SchemaFactory $jsonSchema,
        private readonly Style|null $style,
    ) {
    }

    /**
     * Set a single example of the parameter's potential value.
     *
     * The example SHOULD match the specified schema if present.
     * The example field is mutually exclusive of the examples field.
     *
     * @param mixed $example Any value representing the example
     *
     * @see https://spec.openapis.org/oas/v3.2.0#parameter-object
     */
    final public function example(mixed $example): static
    {
        Assert::null(
            $this->examples,
            'example and examples fields are mutually exclusive. '
            . 'See: https://spec.openapis.org/oas/v3.2.0#parameter-object',
        );

        $clone = clone $this;

        $clone->example = $example;

        return $clone;
    }

    /**
     * Set multiple examples of the parameter's potential value.
     *
     * Each example SHOULD match the specified schema if present.
     * The examples field is mutually exclusive of the example field.
     *
     * @see https://spec.openapis.org/oas/v3.2.0#parameter-object
     */
    final public function examples(ExampleEntry ...$exampleEntry): static
    {
        Assert::null(
            $this->example,
            'examples and example fields are mutually exclusive. '
            . 'See: https://spec.openapis.org/oas/v3.2.0#parameter-object',
        );

        $clone = clone $this;

        $clone->examples = Examples::create(...$exampleEntry);

        return $clone;
    }

    final public function jsonSerialize(): array
    {
        return [
            ...($this->style?->jsonSerialize() ?? []),
            'schema' => $this->jsonSchema,
            'example' => $this->example,
            'examples' => $this->examples,
        ];
    }
}
