<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\Fields\Encoding\Encoding;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\Fields\Encoding\EncodingEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\Examples;

final class MediaType extends ExtensibleObject
{
    private JSONSchema|SchemaFactory|null $schema = null;
    private Examples|null $examples = null;
    private Encoding|null $encoding = null;

    public function schema(JSONSchema|SchemaFactory $schema): self
    {
        $clone = clone $this;

        $clone->schema = $schema;

        return $clone;
    }

    public function examples(ExampleEntry ...$exampleEntry): self
    {
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

        $clone->encoding = Encoding::create(...$encodingEntry);

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'schema' => $this->schema,
            'examples' => $this->examples,
            'encoding' => $this->encoding,
        ]);
    }
}
