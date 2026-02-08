<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\In;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\ParameterLocation;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\CookieParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\HeaderParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\PathParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\QueryParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\SerializationRule;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\Examples;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;
use Webmozart\Assert\Assert;

/**
 * Parameter Object.
 *
 * Describes a single operation parameter. A unique parameter is defined
 * by a combination of a name and location. The name and in fields are required.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#parameter-object
 */
final class Parameter extends ExtensibleObject
{
    private Description|null $description = null;
    private true|null $required = null;
    private true|null $deprecated = null;
    private true|null $allowEmptyValue = null;

    /** @var mixed Example of the parameter's potential value */
    private mixed $example = null;

    private Examples|null $examples = null;

    private function __construct(
        private readonly Name $name,
        private readonly In $in,
        private readonly SerializationRule $serializationRule,
    ) {
    }

    public static function cookie(
        string $name,
        Content|CookieParameter $serialization,
    ): self {
        return new self(Name::create($name), In::cookie(), $serialization);
    }

    public static function header(
        string $name,
        Content|HeaderParameter $serialization,
    ): self {
        return new self(Name::create($name), In::header(), $serialization);
    }

    /**
     * Create a path parameter.
     *
     * Note: Per OAS 3.2 spec, path parameters SHOULD have `required()` set to true:
     * "If the parameter location is 'path', this field is REQUIRED and its value MUST be true."
     *
     * However, this library allows flexibility for frameworks like Laravel that support
     * optional route parameters. Call `->required()` for OAS-compliant documents.
     *
     * @see https://spec.openapis.org/oas/v3.2.0#parameter-object
     */
    public static function path(
        string $name,
        Content|PathParameter $serialization,
    ): self {
        return new self(Name::create($name), In::path(), $serialization);
    }

    public static function query(
        string $name,
        Content|QueryParameter $serialization,
    ): self {
        return new self(Name::create($name), In::query(), $serialization);
    }

    /**
     * Create a querystring parameter.
     *
     * The querystring location treats the entire URL query string as a value.
     * It MUST use the content field (not schema with style) and the media type
     * MUST be application/x-www-form-urlencoded.
     *
     * @see https://spec.openapis.org/oas/v3.2.0#parameter-locations
     */
    public static function querystring(
        string $name,
        Content $serialization,
    ): self {
        return new self(Name::create($name), In::querystring(), $serialization);
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
     * Allow empty value for query parameters.
     *
     * This property is DEPRECATED and only valid for query parameters.
     * Sets the ability to pass empty-valued parameters.
     *
     * @throws \InvalidArgumentException if called on non-query parameters
     *
     * @see https://spec.openapis.org/oas/v3.2.0#parameter-object
     */
    public function allowEmptyValue(): self
    {
        if (ParameterLocation::QUERY->value !== $this->in->value()) {
            throw new \InvalidArgumentException(sprintf('allowEmptyValue is only valid for query parameters, but this parameter has in: "%s". Note: allowEmptyValue is deprecated per OAS 3.2.', $this->in->value()));
        }

        $clone = clone $this;

        $clone->allowEmptyValue = true;

        return $clone;
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
    public function example(mixed $example): self
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
    public function examples(ExampleEntry ...$exampleEntry): self
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

    public function getName(): string
    {
        return $this->name->value();
    }

    public function getLocation(): string
    {
        return $this->in->value();
    }

    public function isRequired(): bool
    {
        return true === $this->required;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'in' => $this->in,
            'description' => $this->description,
            'required' => $this->required,
            'deprecated' => $this->deprecated,
            'allowEmptyValue' => $this->allowEmptyValue,
            ...$this->mergeFields($this->serializationRule),
            'example' => $this->example,
            'examples' => $this->examples,
        ]);
    }
}
