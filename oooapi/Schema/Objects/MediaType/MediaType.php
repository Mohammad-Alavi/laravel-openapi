<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Encoding\Encoding;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Encodings\EncodingEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Encodings\EncodingMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\Examples;
use Webmozart\Assert\Assert;

/**
 * Media Type Object.
 *
 * Provides schema and examples for the media type identified by its key.
 * Each Media Type Object describes the content of a request or response body.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#media-type-object
 */
final class MediaType extends ExtensibleObject
{
    private JSONSchema|SchemaFactory|null $schema = null;

    /**
     * Schema for items in array-valued properties.
     *
     * When a media type schema specifies properties as arrays, this field
     * provides the schema for the items of those arrays. Only applicable
     * for multipart/* media types.
     *
     * @see https://spec.openapis.org/oas/v3.2.0#media-type-object
     */
    private JSONSchema|SchemaFactory|null $itemSchema = null;

    /** @var mixed Example of the media type; mutually exclusive with examples */
    private mixed $example = null;

    private Examples|null $examples = null;
    private EncodingMap|null $encoding = null;

    /** @var Encoding[]|null */
    private array|null $prefixEncoding = null;

    private Encoding|null $itemEncoding = null;

    public function schema(JSONSchema|SchemaFactory $schema): self
    {
        $clone = clone $this;

        $clone->schema = $schema;

        return $clone;
    }

    /**
     * Schema for array items in multipart requests.
     *
     * When a property in the request body schema is an array, this field
     * specifies the schema for items of that array. This is only used
     * with multipart/* media types.
     */
    public function itemSchema(JSONSchema|SchemaFactory $itemSchema): self
    {
        $clone = clone $this;

        $clone->itemSchema = $itemSchema;

        return $clone;
    }

    /**
     * Example of the media type.
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
     * Examples of the media type.
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

    public static function create(): self
    {
        return new self();
    }

    public function encoding(EncodingEntry ...$encodingEntry): self
    {
        $clone = clone $this;

        $clone->encoding = EncodingMap::create(...$encodingEntry);

        return $clone;
    }

    public function prefixEncoding(Encoding ...$encoding): self
    {
        $clone = clone $this;

        $clone->prefixEncoding = $encoding;

        return $clone;
    }

    public function itemEncoding(Encoding $encoding): self
    {
        $clone = clone $this;

        $clone->itemEncoding = $encoding;

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'schema' => $this->schema,
            'itemSchema' => $this->itemSchema,
            'example' => $this->example,
            'examples' => $this->examples,
            'encoding' => $this->encoding,
            'prefixEncoding' => $this->prefixEncoding,
            'itemEncoding' => $this->itemEncoding,
        ]);
    }
}
