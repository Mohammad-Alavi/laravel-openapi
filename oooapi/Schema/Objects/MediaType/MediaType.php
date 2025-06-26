<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\Fields\Encoding\Encoding;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\Fields\Encoding\EncodingEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class MediaType extends ExtensibleObject
{
    private JSONSchema|SchemaFactory|null $schema = null;
    private Example|null $example = null;
    /** @var Example[]|null */
    private array|null $examples = null;
    private Encoding|null $encoding = null;

    private function __construct()
    {
    }

    public function schema(JSONSchema|SchemaFactory|null $schema): self
    {
        $clone = clone $this;

        $clone->schema = $schema;

        return $clone;
    }

    public function example(Example|null $example): self
    {
        $clone = clone $this;

        $clone->example = $example;

        return $clone;
    }

    public function examples(Example ...$example): self
    {
        $clone = clone $this;

        $clone->examples = [] !== $example ? $example : null;

        return $clone;
    }

    public function encoding(EncodingEntry ...$encodingEntry): self
    {
        $clone = clone $this;

        $clone->encoding = Encoding::create(...$encodingEntry);

        return $clone;
    }

    public static function create(): self
    {
        return new self();
    }

    protected function toArray(): array
    {
        $examples = [];
        foreach ($this->examples ?? [] as $example) {
            $examples[$example->key()] = $example;
        }

        return Arr::filter([
            'schema' => $this->schema,
            'example' => $this->example,
            'examples' => [] !== $examples ? $examples : null,
            'encoding' => $this->encoding,
        ]);
    }
}
